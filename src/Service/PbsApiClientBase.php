<?php

namespace CascadePublicMedia\PbsApiExplorer\Service;

use CascadePublicMedia\PbsApiExplorer\Entity\Franchise;
use CascadePublicMedia\PbsApiExplorer\Entity\Genre;
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
     * MediaManagerApiClient constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param FieldMapper $fieldMapper
     * @param ApiValueProcessor $apiValueProcessor
     * @param array $clientConfig
     */
    public function __construct(EntityManagerInterface $entityManager,
                                FieldMapper $fieldMapper,
                                ApiValueProcessor $apiValueProcessor,
                                array $clientConfig)
    {
        $this->client = new Client($clientConfig);
        $this->apiValueProcessor = $apiValueProcessor;
        $this->entityManager = $entityManager;
        $this->fieldMapper = $fieldMapper;
        $this->propertyAccessor = PropertyAccess::createPropertyAccessor();
    }

    /**
     * Update/add all records from the API for a specific Entity class.
     *
     * @param $entityClass
     */
    public function updateAll($entityClass) {
        // Retrieve all existing entities to compare update dates.
        $entities = $this->entityManager
            ->getRepository($entityClass)
            ->findAllIndexedById();

        /**
         * Process the array page by page.
         *
         * The API returns page data in the "meta" key of the return response.
         * This loop will continue to run for all pages until the API no longer
         * returns a value in the $data['meta']['links']['next'] field.
         *
         * @see https://docs.pbs.org/display/CDA/Pagination
         */
        $page = 1;
        while(true) {
            $response = $this->client->get($entityClass::ENDPOINT, ['query' => [
                'page-size' => 50,
                'page' => $page,
            ]]);

            if ($response->getStatusCode() != 200) {
                throw new HttpException($response->getStatusCode());
            }

            $data = json_decode($response->getBody());

            foreach ($data->data as $item) {

                // Update an existing entity or create a new one.
                if (isset($entities[$item->id])) {
                    $entity = $entities[$item->id];
                }
                else {
                    $entity = new $entityClass;
                    $this->propertyAccessor->setValue($entity, 'id', $item->id);
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
    }
}
