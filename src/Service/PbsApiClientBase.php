<?php

namespace CascadePublicMedia\PbsApiExplorer\Service;

use CascadePublicMedia\PbsApiExplorer\Entity\Setting;
use CascadePublicMedia\PbsApiExplorer\Utils\ApiValueProcessor;
use CascadePublicMedia\PbsApiExplorer\Utils\FieldMapper;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Expr\Comparison;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\PersistentCollection;
use GuzzleHttp\Client;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
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
    protected $fieldMapper;

    /**
     * @var ApiValueProcessor
     */
    protected $apiValueProcessor;

    /**
     * @var PropertyAccessorInterface
     */
    protected $propertyAccessor;

    /**
     * @var array
     */
    protected $requiredSettings = [];

    /**
     * @var array
     */
    protected $settings = [];

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

        /** @var Setting[] settings */
        $this->settings = $entityManager
            ->getRepository(Setting::class)
            ->findAllIndexedById();
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
        foreach ($this->requiredSettings as $id => $name) {
            if (!isset($this->settings[$id]) || empty($this->settings[$id])) {
                return FALSE;
            }
        }
        return TRUE;
    }

    /**
     * Get a specific setting value for this client.
     *
     * @param $key
     *   Field name of setting as found in Setting repository.
     *
     * @return bool|mixed
     *   The setting value of FALSE is the setting does not exist.
     */
    public function getSetting($key) {
        if (!isset($this->settings[$key])) {
            return FALSE;
        }
        return $this->settings[$key]->getValue();
    }

    /**
     * Update all entities of a specific class from Entity ENDPOINT constant.
     *
     * This method is meant for simple queries where all instances can be
     * retrieved from the API and updated locally from a single base endpoint.
     *
     * @param $entityClass
     *   The Entity class to be updated.
     * @param array $config
     *   (optional) Additional configuration options to pass on to the update
     *   method (@see PbsApiClientBase::update()).
     *
     * @return array
     *
     * @see self::update()
     */
    public function updateAllByEntityClass($entityClass, array $config = []) {
        // Retrieve all existing entities to compare update dates.
        $entities = $this->entityManager
            ->getRepository($entityClass)
            ->findAll();
        $entities = new ArrayCollection($entities);
        return $this->update($entityClass, $entities, $entityClass::ENDPOINT, $config);
    }

    /**
     * Update/add all records from the API for a URL.
     *
     * The API returns page data in the "meta" key of the return response.
     * This loop will continue to run for all pages until the API no longer
     * returns a value in the $json['meta']['links']['next'] field.
     *
     * @see https://docs.pbs.org/display/CDA/Pagination
     *
     * @param $entityClass
     *   The Entity class to be updated.
     * @param Collection|ArrayCollection|PersistentCollection $entities
     *   Existing entities in the system to compare against. While Collection is
     *   enforced, this is assumed to be either an ArrayCollection or
     *   PersistentCollection supporting the `matching` method.
     * @param string $url
     *   The API URL to query.
     * @param array $config
     *   (optional) An array of additional configuration options.
     *   @see createUpdateConfig
     *
     * @return array
     *   Stats about the updates keyed by:
     *    - 'add': Number of locally added records.
     *    - 'update': Number of locally updated records.
     *    - 'noop': Number of unaffected records.
     *
     * @todo Delete local records for items no longer in API?
     */
    public function update($entityClass, Collection $entities, $url, array $config)
    {
        $stats = ['add' => 0, 'update' => 0, 'noop' => 0];
        $page = 1;
        $config = self::createUpdateConfig($config);

        while(true) {
            $response = $this->client->get($url, [
                'query' => $config['queryParameters'] + ['page' => $page],
            ]);

            if ($response->getStatusCode() != 200) {
                throw new HttpException($response->getStatusCode());
            }

            $json = json_decode($response->getBody());
            if (!isset($json->{$config['dataKey']})) {
                throw new BadRequestHttpException('Configured data key 
                    not found in response.');
            }
            else {
                $items = $json->{$config['dataKey']};
            }

            // Some requests return a single response as an object instead of an
            // array. The object must be converted to an array for proper
            // handling.
            if (is_object($items)) {
                $items = [$items];
            }

            foreach ($items as $item) {
                // Check for an existing instance.
                $criteria = new Criteria(new Comparison('id', '=', $item->id));
                $entity = $entities->matching($criteria)->first();

                // Update an existing entity or create a new one.
                if ($entity) {
                    $op = 'update';
                }
                else {
                    $entity = new $entityClass;
                    $this->propertyAccessor->setValue($entity, 'id', $item->id);

                    // Add any supplied extra properties.
                    foreach ($config['extraProps'] as $property => $value) {
                        $this->propertyAccessor->setValue(
                            $entity,
                            $property,
                            $value
                        );
                    }

                    $op = 'add';
                }

                // Compare date in "updated_at" field for entities that support it.
                if (isset($item->attributes->updated_at) && !$config['force']
                    && method_exists($entity, 'getUpdated')) {
                    $entity_updated = $entity->getUpdated();
                    $record_updated = $this->apiValueProcessor::processDateTimeString(
                        $item->attributes->updated_at
                    );

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
                    $this->apiValueProcessor->process(
                        $entity,
                        $field_name,
                        $value
                    );
                }

                // Merge changes to the entity.
                $this->entityManager->merge($entity);
                $stats[$op]++;
            }

            // If another page is available, continue to it. Otherwise, end
            // execution of this loop.
            if (isset($json->links) && isset($json->links->next)
                && !empty($json->links->next)) {
                $query_string = parse_url($json->links->next, PHP_URL_QUERY);
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

    /**
     * Create a config array from supplied options and defaults.
     *
     * @param array $config
     *   (optional) An array of configuration options supporting the keys:
     *    - queryParameters (array): Query parameters to add to the request.
     *    - extraProps (array): Additional properties to be applied to all *new*
     *        entities. This is meant to help with endpoints that do not provide
     *        fields for locally required relationships (e.g. the
     *        `seasons/{id}/episodes` endpoint does not provide `show` or
     *        `season` data). The array should contain key => value entries for
     *        a valid property name for the Entity and the value that will be
     *        set. E.g. -- ['show' => Show object, 'season' => Season object];
     *    - dataKey (string): The key of the JSON response object containing
     *        the data to traverse (should be an array or object). Typically
     *        this will be just "data".
     *    - force (bool): Set to TRUE to ignore the "updated_at" field check and
     *        always process the update.
     *
     * @return array
     *   The configuration array for the update.
     *
     * @see update
     */
    protected function createUpdateConfig($config = []) {
       return $config + [
           'dataKey' => 'data',
           'extraProps' => [],
           'force' => FALSE,
           'queryParameters' => [],
       ];
    }
}
