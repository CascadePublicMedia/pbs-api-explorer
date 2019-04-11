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
    /**
     * @var string
     */
    protected static $endpoint = 'https://station.services.pbs.org/api/public/v1/';

    public function __construct(EntityManagerInterface $entityManager, FieldMapper $fieldMapper, ApiValueProcessor $apiValueProcessor)
    {
        parent::__construct($entityManager, $fieldMapper, $apiValueProcessor);
        $this->createClient(['base_uri' => self::$endpoint]);
    }
}
