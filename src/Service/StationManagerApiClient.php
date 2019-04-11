<?php

namespace CascadePublicMedia\PbsApiExplorer\Service;

use CascadePublicMedia\PbsApiExplorer\Entity\Setting;
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
    /**
     * StationManagerApiClient constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param FieldMapper $fieldMapper
     * @param ApiValueProcessor $apiValueProcessor
     *
     * @throws \Exception
     */
    public function __construct(EntityManagerInterface $entityManager, FieldMapper $fieldMapper, ApiValueProcessor $apiValueProcessor)
    {
        /** @var Setting[] $settings */
        $settings = $entityManager
            ->getRepository(Setting::class)
            ->findByIdPrefix('station_manager');

        $required_fields = [
            'station_manager_base_uri' => 'Endpoint',
            'station_manager_client_id' => 'Client ID',
            'station_manager_client_secret' => 'Client secret',
        ];

        foreach ($required_fields as $id => $value) {
            if (!isset($settings[$id])) {
                throw new \Exception("Required setting {$value} missing.");
            }
        }

        $clientConfig = [
            'base_uri' => $settings['station_manager_base_uri']->getValue(),
            'auth' => [
                $settings['station_manager_client_id']->getValue(),
                $settings['station_manager_client_secret']->getValue()
            ],
        ];
        parent::__construct($entityManager, $fieldMapper, $apiValueProcessor, $clientConfig);
    }
}
