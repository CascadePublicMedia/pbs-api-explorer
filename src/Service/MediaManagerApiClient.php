<?php

namespace CascadePublicMedia\PbsApiExplorer\Service;

use CascadePublicMedia\PbsApiExplorer\Entity\Asset;
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
    protected $requiredSettings = [
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
     */
    public function updateEpisodesByShowId($showId) {
        /** @var Show $show */
        $show = $this->entityManager
            ->getRepository(Show::class)
            ->find($showId);

        foreach ($show->getSeasons() as $season) {
            $episodes = $season->getEpisodes();

            parent::update(
                Episode::class,
                $episodes,
                "seasons/{$season->getId()}/episodes/",
                [
                    'extraProps' => ['show' => $show, 'season' => $season],
                    'queryParameters' => ['fetch-related' => TRUE],
                ]
            );

            foreach ($episodes as $episode) {
                $this->updateAssetsByEpisodeId($episode->getId());
            }
        }
    }

    /**
     * Update Assets data associated with a specific Episode.
     *
     * This function assumes that the Episode already exists locally and has
     * associated Assets. Regular Episode updates will bring in limited Asset
     * data through the `fetch-related` query parameter, but many key fields
     * are missing and only accessible from the /assets/ API endpoint.
     *
     * @param $episodeId
     */
    public function updateAssetsByEpisodeId($episodeId) {
        /** @var Episode $episode */
        $episode = $this->entityManager
            ->getRepository(Episode::class)
            ->find($episodeId);

        $assets = $episode->getAssets();
        foreach ($assets as $asset) {
            parent::update(
                Asset::class,
                $assets,
                Asset::ENDPOINT . "/{$asset->getId()}/",
                ['force' => TRUE]
            );
        }
    }
}
