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
     * @param $entityClass
     * @return object[]
     */
    public function updateAndGetByEntityClass($entityClass) {
        $this->update($entityClass);
        $entities = $this->entityManager
            ->getRepository($entityClass)
            ->findAll();
        return $entities;
    }

    /**
     * @param $entityClass
     * @param int $page
     */
    public function update($entityClass, $page = 1) {
        $response = $this->client->get($entityClass::ENDPOINT, ['query' => [
            'page-size' => 50,
            'page' => $page,
        ]]);

        if ($response->getStatusCode() != 200) {
            throw new HttpException($response->getStatusCode());
        }

        $data = json_decode($response->getBody());

        foreach ($data->data as $item) {
            $entity = new $entityClass;
            $this->propertyAccessor->setValue($entity, 'id', $item->id);

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

            $this->entityManager->merge($entity);
        }

        $this->entityManager->flush();

        if ($data->links->next) {
            $query_string = parse_url($data->links->next, PHP_URL_QUERY);
            if ($query_string) {
                parse_str($query_string, $query);
                if (isset($query['page'])) {
                    sleep(1);
                    $this->update($entityClass, $query['page']);
                }
            }
        }
    }
}
