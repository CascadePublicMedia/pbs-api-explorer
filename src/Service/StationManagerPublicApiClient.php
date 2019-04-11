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
    const ENDPOINT = 'https://station.services.pbs.org/api/public/v1/';

    public function __construct(EntityManagerInterface $entityManager, FieldMapper $fieldMapper, ApiValueProcessor $apiValueProcessor)
    {
        $clientConfig = ['base_uri' => $this::ENDPOINT];
        parent::__construct($entityManager, $fieldMapper, $apiValueProcessor, $clientConfig);
    }
}
