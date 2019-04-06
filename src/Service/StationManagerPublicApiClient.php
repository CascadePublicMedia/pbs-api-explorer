<?php

namespace CascadePublicMedia\PbsApiExplorer\Service;

use CascadePublicMedia\PbsApiExplorer\Utils\ApiValueProcessor;
use CascadePublicMedia\PbsApiExplorer\Utils\FieldMapper;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class StationManagerPublicApiClient
 *
 * @package CascadePublicMedia\PbsApiExplorer\Service
 */
class StationManagerPublicApiClient extends PbsApiClientBase
{
    public function __construct(EntityManagerInterface $entityManager, FieldMapper $fieldMapper, ApiValueProcessor $apiValueProcessor)
    {
        $clientConfig = ['base_uri' => getenv('STATION_MANAGER_PUBLIC_URI')];
        parent::__construct($entityManager, $fieldMapper, $apiValueProcessor, $clientConfig);
    }
}
