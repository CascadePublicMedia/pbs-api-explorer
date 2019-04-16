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
     * @var array
     */
    protected $requiredFields = [
        'media_manager_base_uri' => 'Endpoint',
        'media_manager_client_id' => 'Client ID',
        'media_manager_client_secret' => 'Client secret',
    ];

    /**
     * MediaManagerApiClient constructor.
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
                ->findByIdPrefix('media_manager');

            $this->createClient([
                'base_uri' => $settings['media_manager_base_uri']->getValue(),
                'auth' => [
                    $settings['media_manager_client_id']->getValue(),
                    $settings['media_manager_client_secret']->getValue()
                ],
            ]);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function updateAll($entityClass, array $parameters = [])
    {
        return parent::updateAll($entityClass, [
            'fetch-related' => TRUE,
        ]);
    }
}
