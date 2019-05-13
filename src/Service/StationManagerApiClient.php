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
     * @var array
     */
    protected $requiredSettings = [
        'station_manager_base_uri' => 'Endpoint',
        'station_manager_client_id' => 'Client ID',
        'station_manager_client_secret' => 'Client secret',
    ];

    /**
     * StationManagerApiClient constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param FieldMapper $fieldMapper
     * @param ApiValueProcessor $apiValueProcessor
     */
    public function __construct(EntityManagerInterface $entityManager, FieldMapper $fieldMapper, ApiValueProcessor $apiValueProcessor)
    {
        parent::__construct($entityManager, $fieldMapper, $apiValueProcessor);

        if ($this->isConfigured()) {
            /** @var Setting[] $settings */
            $settings = $entityManager
                ->getRepository(Setting::class)
                ->findByIdPrefix('station_manager');

            $this->createClient([
                'base_uri' => $settings['station_manager_base_uri']->getValue(),
                'auth' => [
                    $settings['station_manager_client_id']->getValue(),
                    $settings['station_manager_client_secret']->getValue()
                ],
            ]);
        }
    }
}
