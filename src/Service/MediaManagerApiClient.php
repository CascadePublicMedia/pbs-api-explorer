<?php

namespace CascadePublicMedia\PbsApiExplorer\Service;

use CascadePublicMedia\PbsApiExplorer\Entity\Episode;
use CascadePublicMedia\PbsApiExplorer\Entity\Setting;
use CascadePublicMedia\PbsApiExplorer\Entity\Show;
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
     * Update all Episode instances for a Show.
     *
     * @param string $showId
     *
     * TODO: Add fetch-related and process assets.
     */
    public function updateEpisodesByShowId($showId) {
        /** @var Show $show */
        $show = $this->entityManager
            ->getRepository(Show::class)
            ->find($showId);

        foreach ($show->getSeasons() as $season) {
            parent::update(
                Episode::class,
                $season->getEpisodes(),
                "seasons/{$season->getId()}/episodes/",
                ['fetch-related' => TRUE],
                ['show' => $show, 'season' => $season]
            );
        }
    }
}
