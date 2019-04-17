<?php

namespace CascadePublicMedia\PbsApiExplorer\Service;

use CascadePublicMedia\PbsApiExplorer\Entity\Franchise;
use CascadePublicMedia\PbsApiExplorer\Entity\Genre;
use CascadePublicMedia\PbsApiExplorer\Entity\Setting;
use CascadePublicMedia\PbsApiExplorer\Entity\Show;
use CascadePublicMedia\PbsApiExplorer\Utils\ApiValueProcessor;
use CascadePublicMedia\PbsApiExplorer\Utils\FieldMapper;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Client;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

/**
 * Class PbsApiClientBase
 *
 * @package CascadePublicMedia\PbsApiExplorer\Service
 */
class PbsApiClientBase
{
    /**
    * @var Client
    */
    protected $client;

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var FieldMapper
     */
    private $fieldMapper;

    /**
     * @var ApiValueProcessor
     */
    private $apiValueProcessor;

    /**
     * @var PropertyAccessorInterface
     */
    private $propertyAccessor;

    /**
     * @var array
     */
    protected $requiredFields = [];

    /**
     * MediaManagerApiClient constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param FieldMapper $fieldMapper
     * @param ApiValueProcessor $apiValueProcessor
     */
    public function __construct(EntityManagerInterface $entityManager,
                                FieldMapper $fieldMapper,
                                ApiValueProcessor $apiValueProcessor)
    {
        $this->apiValueProcessor = $apiValueProcessor;
        $this->entityManager = $entityManager;
        $this->fieldMapper = $fieldMapper;
        $this->propertyAccessor = PropertyAccess::createPropertyAccessor();
    }

    public function createClient($config) {
        $this->client = new Client($config);
    }

    /**
     * Check if all required Setting values exist.
     *
     * @return bool
     */
    public function isConfigured() {
        $settings = $this->entityManager
            ->getRepository(Setting::class)
            ->findAllIndexedById();
        foreach ($this->requiredFields as $id => $name) {
            if (!isset($settings[$id]) || empty($settings[$id])) {
                return FALSE;
            }
        }
        return TRUE;
    }


    /**
     * Update all entities of a specific class from Entity ENDPOINT constant.
     *
     * This method is meant for simple queries where all instances can be
     * retrieved from the API and updated locally from a single base endpoint.
     *
     * @param $entityClass
     *   The Entity class to be updated.
     * @param array $queryParameters
     *   (optional) Query parameters to add to the request.
     *
     * @return array
     *
     * @see self::update()
     */
    public function updateAllByEntityClass($entityClass, array $queryParameters = []) {
        // Retrieve all existing entities to compare update dates.
        $entities = $this->entityManager
            ->getRepository($entityClass)
            ->findAllIndexedById();

        return $this->update(
            $entityClass,
            $entities,
            $entityClass::ENDPOINT,
            $queryParameters
        );
    }

    /**
     * Update/add all records from the API for a URL.
     *
     * The API returns page data in the "meta" key of the return response.
     * This loop will continue to run for all pages until the API no longer
     * returns a value in the $data['meta']['links']['next'] field.
     *
     * @see https://docs.pbs.org/display/CDA/Pagination
     *
     * @param $entityClass
     *   The Entity class to be updated.
     * @param array $entities
     *   Existing entities in the system to compare against. This array must be
     *   indexed by the same ID that will be returned from the PBS API being
     *   queried.
     * @param string $url
     *   The API URL to query.
     * @param array $queryParameters
     *   (optional) Query parameters to add to the request.
     * @param array $extraProps
     *   (optional) Additional properties to be applied to all *new* entities.
     *   This is meant to help with endpoints that do not provide fields for
     *   locally required relationships (e.g. the `seasons/{id}/episodes`
     *   endpoint does not provide `show` or `season` data). The array should
     *   contain key => value entries for a valid property name for the Entity
     *   and the value that will be set. E.g. --
     *     $additionalProps = [
     *       'show' => Show object
     *       'season' => Season object
     *     ];
     *
     * @return array
     *   Stats about the updates keyed by:
     *    - 'add': Number of locally added records.
     *    - 'update': Number of locally updated records.
     *    - 'noop': Number of unaffected records.
     *
     * @todo Delete local records for items no longer in API?
     */
    public function update($entityClass,
                           $entities,
                           $url,
                           array $queryParameters = [],
                           array $extraProps = [])
    {
        $stats = ['add' => 0, 'update' => 0, 'noop' => 0];
        $page = 1;

        while(true) {
            $response = $this->client->get($url, [
                'query' => $queryParameters + ['page' => $page],
            ]);

            if ($response->getStatusCode() != 200) {
                throw new HttpException($response->getStatusCode());
            }

            $data = json_decode($response->getBody());

            foreach ($data->data as $item) {

                // Update an existing entity or create a new one.
                if (isset($entities[$item->id])) {
                    $entity = $entities[$item->id];
                    $op = 'update';
                }
                else {
                    $entity = new $entityClass;
                    $this->propertyAccessor->setValue($entity, 'id', $item->id);

                    // Add any supplied extra properties.
                    foreach ($extraProps as $property => $value) {
                        $this->propertyAccessor->setValue(
                            $entity,
                            $property,
                            $value
                        );
                    }

                    $op = 'add';
                }

                // Compare date in "updated_at" field for entities that support it.
                if (isset($item->attributes->updated_at)
                    && method_exists($entity, 'getUpdated')) {
                    $entity_updated = $entity->getUpdated();
                    $record_updated = $this->apiValueProcessor
                        ->processValue('updated_at', $item->attributes->updated_at);

                    // If the record update date is not greater than the entity
                    // updated date, do not continue with the update process.
                    if ($record_updated && $entity_updated
                        && $record_updated->format('Y-m-d H:i:s') <= $entity_updated->format('Y-m-d H:i:s')) {
                        $stats['noop']++;
                        continue;
                    }
                }

                // Iterate and update all entity attributes from the API
                // record.
                foreach ($item->attributes as $field_name => $value) {
                    if (is_array($value)) {
                        $this->apiValueProcessor->processArray($entity, $field_name, $value);
                    }
                    else {
                        $this->propertyAccessor->setValue(
                            $entity,
                            $this->fieldMapper->map($field_name),
                            $this->apiValueProcessor->processValue($field_name, $value)
                        );
                    }
                }

                // Merge changes to the entity.
                $this->entityManager->merge($entity);
                $stats[$op]++;
            }

            // If another page is available, continue to it. Otherwise, end
            // execution of this loop.
            if (isset($data->links) && $data->links->next) {
                $query_string = parse_url($data->links->next, PHP_URL_QUERY);
                if ($query_string) {
                    parse_str($query_string, $query);
                    if (isset($query['page'])) {
                        $page = $query['page'];
                        continue;
                    }
                }
            }

            break;
        }

        // Flush any changes.
        $this->entityManager->flush();

        return $stats;
    }
}
