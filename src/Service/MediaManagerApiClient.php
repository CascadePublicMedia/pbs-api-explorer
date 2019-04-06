<?php

namespace CascadePublicMedia\PbsApiExplorer\Service;

use CascadePublicMedia\PbsApiExplorer\Utils\ApiValueProcessor;
use CascadePublicMedia\PbsApiExplorer\Utils\FieldMapper;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class MediaManagerApiClient
 *
 * @package CascadePublicMedia\PbsApiExplorer\Service
 */
class MediaManagerApiClient extends PbsApiClientBase
{
    public function __construct(EntityManagerInterface $entityManager, FieldMapper $fieldMapper, ApiValueProcessor $apiValueProcessor)
    {
        $clientConfig = [
            'base_uri' => getenv('MEDIA_MANAGER_BASE_URI'),
            'auth' => [
                getenv('MEDIA_MANAGER_CLIENT_ID'),
                getenv('MEDIA_MANAGER_CLIENT_SECRET')
            ],
        ];
        parent::__construct($entityManager, $fieldMapper, $apiValueProcessor, $clientConfig);
    }
}
