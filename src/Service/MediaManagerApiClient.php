<?php

namespace CascadePublicMedia\PbsApiExplorer\Service;

use CascadePublicMedia\PbsApiExplorer\Entity\Setting;
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
    /**
     * MediaManagerApiClient constructor.
     * @param EntityManagerInterface $entityManager
     * @param FieldMapper $fieldMapper
     * @param ApiValueProcessor $apiValueProcessor
     * @throws \Exception
     */
    public function __construct(EntityManagerInterface $entityManager, FieldMapper $fieldMapper, ApiValueProcessor $apiValueProcessor)
    {
        $settings = $entityManager
            ->getRepository(Setting::class)
            ->findByIdPrefix('media_manager');

        $required_fields = [
            'media_manager_base_uri',
            'media_manager_client_id',
            'media_manager_client_secret',
        ];

        foreach ($required_fields as $field) {
            if (!isset($settings[$field])) {
                throw new \Exception("Required setting {$field} missing.");
            }
        }

        $clientConfig = [
            'base_uri' => $settings['media_manager_base_uri']->getValue(),
            'auth' => [
                $settings['media_manager_client_id']->getValue(),
                $settings['media_manager_client_secret']->getValue()
            ],
        ];
        parent::__construct($entityManager, $fieldMapper, $apiValueProcessor, $clientConfig);
    }
}
