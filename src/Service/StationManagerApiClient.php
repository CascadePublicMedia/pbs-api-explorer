<?php

namespace CascadePublicMedia\PbsApiExplorer\Service;

use CascadePublicMedia\PbsApiExplorer\Utils\ApiValueProcessor;
use CascadePublicMedia\PbsApiExplorer\Utils\FieldMapper;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class StationManagerApiClient
 *
 * @package CascadePublicMedia\PbsApiExplorer\Service
 */
class StationManagerApiClient extends PbsApiClientBase
{
    public function __construct(EntityManagerInterface $entityManager, FieldMapper $fieldMapper, ApiValueProcessor $apiValueProcessor)
    {
        $clientConfig = [
            'base_uri' => getenv('STATION_MANAGER_INTERNAL_URI'),
            'auth' => [
                getenv('STATION_MANAGER_CLIENT_ID'),
                getenv('STATION_MANAGER_CLIENT_SECRET')
            ],
        ];
        parent::__construct($entityManager, $fieldMapper, $apiValueProcessor, $clientConfig);
    }
}
