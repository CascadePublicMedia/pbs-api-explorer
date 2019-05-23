<?php

namespace CascadePublicMedia\PbsApiExplorer\Service;

use CascadePublicMedia\PbsApiExplorer\Entity\Asset;
use CascadePublicMedia\PbsApiExplorer\Entity\ChangelogEntry;
use CascadePublicMedia\PbsApiExplorer\Entity\Episode;
use CascadePublicMedia\PbsApiExplorer\Entity\Setting;
use CascadePublicMedia\PbsApiExplorer\Entity\Show;
use CascadePublicMedia\PbsApiExplorer\Utils\ApiValueProcessor;
use CascadePublicMedia\PbsApiExplorer\Utils\FieldMapper;
use DateInterval;
use DateTime;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Expr\Comparison;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Contracts\Cache\ItemInterface;

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
    public function __construct(EntityManagerInterface $entityManager,
                                FieldMapper $fieldMapper,
                                ApiValueProcessor $apiValueProcessor)
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
     * @return array
     */
    public function updateEpisodesByShowId($showId) {
        $stats = ['add' => 0, 'update' => 0, 'noop' => 0];

        /** @var Show $show */
        $show = $this->entityManager
            ->getRepository(Show::class)
            ->find($showId);

        foreach ($show->getSeasons() as $season) {
            $episodes = $season->getEpisodes();

            $update_stats = parent::update(
                Episode::class,
                $episodes,
                "seasons/{$season->getId()}/episodes/",
                [
                    'extraProps' => ['show' => $show, 'season' => $season],
                    'queryParameters' => ['fetch-related' => TRUE],
                ]
            );

            foreach ($update_stats as $key => $count) {
                $stats[$key] += $count;
            }

            foreach ($episodes as $episode) {
                $this->updateAssetsByEpisode($episode);
            }
        }

        return $stats;
    }

    /**
     * Update Assets data associated with a specific Episode.
     *
     * This function assumes that the Episode already exists locally and has
     * associated Assets. Regular Episode updates will bring in limited Asset
     * data through the `fetch-related` query parameter, but many key fields
     * are missing and only accessible from the /assets/ API endpoint.
     *
     * @param Episode $episode
     */
    public function updateAssetsByEpisode($episode) {
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

    /**
     * Add new change log entries available since the last update.
     *
     * @return array
     *   Stats about the operation (adds only).
     *
     * @throws \Exception
     */
    public function updateChangelog() {
        $date_format = ApiValueProcessor::MEDIA_MANAGER_API_DATE_FORMAT;

        // The changelog endpoint receives thousands of entries per
        // hour, so this update process is limited to two hours.
        $now_pt2h = new DateTime();
        $now_pt2h->sub(new DateInterval('PT2H'));
        $entity = $this->entityManager
            ->getRepository(ChangelogEntry::class)
            ->findLastUpdated();
        if ($entity && $entity->getTimestamp() > $now_pt2h) {
            $since = $entity->getTimestamp();
        }
        else {
            $since = $now_pt2h;
        }

        $added = 0;
        $page = 1;
        $config = self::createQueryConfig([
            'queryParameters' => [
                'since' => $since->format($date_format)
            ]
        ]);

        while(true) {
            $response = $this->client->get(ChangelogEntry::ENDPOINT, [
                'query' => $config['queryParameters'] + ['page' => $page],
            ]);

            if ($response->getStatusCode() != 200) {
                throw new HttpException($response->getStatusCode());
            }

            $json = json_decode($response->getBody());
            $items = $json->data;

            foreach ($items as $item) {
                /** @var ChangelogEntry $entity */
                $entity = new ChangelogEntry();
                $entity->setType($item->type);
                $entity->setResourceId($item->id);

                // Iterate and update all entity attributes from the API
                // record.
                foreach ($item->attributes as $field_name => $value) {
                    $this->apiValueProcessor->process(
                        $entity,
                        $field_name,
                        $value
                    );
                }

                $this->entityManager->persist($entity);
                $added++;
            }

            if ($page = $this->getNextPage($json)) {
                continue;
            }

            break;
        }

        // Flush any changes.
        $this->entityManager->flush();

        return ['add' => $added, 'update' => 0, 'noop' => 0];
    }
}
