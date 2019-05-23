<?php

namespace CascadePublicMedia\PbsApiExplorer\Controller;

use CascadePublicMedia\PbsApiExplorer\DataTable\Type as DataTableType;
use CascadePublicMedia\PbsApiExplorer\Entity;
use CascadePublicMedia\PbsApiExplorer\Service\MediaManagerApiClient;
use Doctrine\ORM\EntityManagerInterface;
use Omines\DataTablesBundle\DataTableFactory;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class MediaManagerController
 *
 * @package CascadePublicMedia\PbsApiExplorer\Controller
 */
class MediaManagerController extends ControllerBase
{
    private static $notConfigured = 'The Media Manager API has not been configured. Visit Settings to configure it.';

    /**
     * @Route("/media-manager", name="media_manager")
     * @Security("is_granted('ROLE_USER')")
     *
     * @return Response
     */
    public function index()
    {
        return $this->render('media_manager/index.html.twig', [
            'controller_name' => 'MediaManagerController',
        ]);
    }

    /**
     * @Route("/media-manager/genres", name="media_manager_genres")
     * @Security("is_granted('ROLE_USER')")
     *
     * @param DataTableFactory $dataTableFactory
     * @param Request $request
     *
     * @return Response
     */
    public function genres(DataTableFactory $dataTableFactory, Request $request) {
        $table = $dataTableFactory->createFromType(DataTableType\GenresTableType::class)
            ->handleRequest($request);
        if ($table->isCallback()) {
            return $table->getResponse();
        }
        return $this->render('datatable.html.twig', [
            'datatable' => $table,
            'title' => 'Genres',
            'subtitle' => self::createIconLink(
                '"docs"',
                'https://docs.pbs.org/display/CDA/Shows#Shows-genreTableGenreList'
            ),
            'update_route' => 'media_manager_genres_update'
        ]);
    }

    /**
     * @Route("/media-manager/genres/update", name="media_manager_genres_update")
     * @Security("is_granted('ROLE_ADMIN')")
     *
     * @param MediaManagerApiClient $apiClient
     *
     * @return RedirectResponse
     */
    public function genres_update(MediaManagerApiClient $apiClient) {
        if (!$apiClient->isConfigured()) {
            throw new NotFoundHttpException(self::$notConfigured);
        }
        $stats = $apiClient->updateAllByEntityClass(Entity\Genre::class);
        $this->flashUpdateStats($stats);
        return $this->redirectToRoute('media_manager_genres');
    }

    /**
     * @Route("/media-manager/genres/{id}", name="media_manager_genres_genre")
     * @Security("is_granted('ROLE_USER')")
     *
     * @param string $id
     * @param EntityManagerInterface $entityManager
     *
     * @return Response
     */
    public function genre($id, EntityManagerInterface $entityManager) {
        $entity = $entityManager
            ->getRepository(Entity\Genre::class)
            ->findEager($id);
        if (!$entity) {
            throw new NotFoundHttpException();
        }
        return $this->render('media_manager/genre.html.twig', [
            'entity' => $entity,
            'type' => $entity::NAME,
        ]);
    }

    /**
     * @Route("/media-manager/franchises", name="media_manager_franchises")
     * @Security("is_granted('ROLE_USER')")
     *
     * @param DataTableFactory $dataTableFactory
     * @param Request $request
     *
     * @return Response
     */
    public function franchises(DataTableFactory $dataTableFactory, Request $request) {
        $table = $dataTableFactory->createFromType(DataTableType\FranchisesTableType::class)
            ->handleRequest($request);
        if ($table->isCallback()) {
            return $table->getResponse();
        }
        return $this->render('datatable.html.twig', [
            'datatable' => $table,
            'title' => 'Franchises',
            'subtitle' => self::createIconLink(
                'docs',
                'https://docs.pbs.org/display/CDA/Franchises'
            ),
            'update_route' => 'media_manager_franchises_update'
        ]);
    }

    /**
     * @Route("/media-manager/franchises/update", name="media_manager_franchises_update")
     * @Security("is_granted('ROLE_ADMIN')")
     *
     * @param MediaManagerApiClient $apiClient
     *
     * @return RedirectResponse
     */
    public function franchises_update(MediaManagerApiClient $apiClient) {
        if (!$apiClient->isConfigured()) {
            throw new NotFoundHttpException(self::$notConfigured);
        }
        $stats = $apiClient->updateAllByEntityClass(
            Entity\Franchise::class,
            ['queryParameters' => ['fetch-related' => TRUE]]
        );
        $this->flashUpdateStats($stats);

        return $this->redirectToRoute('media_manager_franchises');
    }

    /**
     * @Route("/media-manager/franchises/{id}", name="media_manager_franchises_franchise")
     * @Security("is_granted('ROLE_USER')")
     *
     * @param string $id
     * @param EntityManagerInterface $entityManager
     *
     * @return Response
     */
    public function franchise($id, EntityManagerInterface $entityManager) {
        $entity = $entityManager
            ->getRepository(Entity\Franchise::class)
            ->findEager($id);
        if (!$entity) {
            throw new NotFoundHttpException();
        }
        return $this->render('media_manager/franchise.html.twig', [
            'entity' => $entity,
            'type' => $entity::NAME,
        ]);
    }

    /**
     * @Route("/media-manager/shows", name="media_manager_shows")
     * @Security("is_granted('ROLE_USER')")
     *
     * @param DataTableFactory $dataTableFactory
     * @param Request $request
     *
     * @return Response
     */
    public function shows(DataTableFactory $dataTableFactory, Request $request) {
        $table = $dataTableFactory->createFromType(DataTableType\ShowsTableType::class)
            ->handleRequest($request);
        if ($table->isCallback()) {
            return $table->getResponse();
        }
        return $this->render('datatable.html.twig', [
            'datatable' => $table,
            'title' => 'Shows',
            'subtitle' => self::createIconLink(
                'docs',
                'https://docs.pbs.org/display/CDA/Shows'
            ),
            'update_route' => 'media_manager_shows_update',
        ]);
    }

    /**
     * @Route("/media-manager/shows/update", name="media_manager_shows_update")
     * @Security("is_granted('ROLE_ADMIN')")
     *
     * @param MediaManagerApiClient $apiClient
     *
     * @return RedirectResponse
     */
    public function shows_update(MediaManagerApiClient $apiClient) {
        if (!$apiClient->isConfigured()) {
            throw new NotFoundHttpException(self::$notConfigured);
        }
        $stats = $apiClient->updateAllByEntityClass(
            Entity\Show::class,
            ['queryParameters' => ['fetch-related' => TRUE]]
        );
        $this->flashUpdateStats($stats);
        return $this->redirectToRoute('media_manager_shows');
    }

    /**
     * @Route("/media-manager/shows/{id}", name="media_manager_shows_show")
     * @Security("is_granted('ROLE_USER')")
     *
     * @param string $id
     * @param EntityManagerInterface $entityManager
     *
     * @return Response
     */
    public function show($id, EntityManagerInterface $entityManager) {
        $entity = $entityManager
            ->getRepository(Entity\Show::class)
            ->findEager($id);
        if (!$entity) {
            throw new NotFoundHttpException();
        }
        return $this->render('media_manager/show.html.twig', [
            'entity' => $entity,
            'type' => $entity::NAME,
        ]);
    }

    /**
     * @Route("/media-manager/shows/{showId}/episodes/update", name="media_manager_episodes_update")
     * @Security("is_granted('ROLE_ADMIN')")
     *
     * @param string $showId
     * @param MediaManagerApiClient $apiClient
     *
     * @return RedirectResponse
     */
    public function show_episodes_update($showId, MediaManagerApiClient $apiClient) {
        if (!$apiClient->isConfigured()) {
            throw new NotFoundHttpException(self::$notConfigured);
        }
        $stats = $apiClient->updateEpisodesByShowId($showId);
        $this->flashUpdateStats($stats);
        return $this->redirectToRoute('media_manager_shows_show', [
            'id' => $showId
        ]);
    }

    /**
     * @Route("/media-manager/seasons", name="media_manager_seasons")
     * @Security("is_granted('ROLE_USER')")
     *
     * @param DataTableFactory $dataTableFactory
     * @param Request $request
     *
     * @return Response
     */
    public function seasons(DataTableFactory $dataTableFactory, Request $request) {
        $table = $dataTableFactory->createFromType(DataTableType\SeasonsTableType::class)
            ->handleRequest($request);
        if ($table->isCallback()) {
            return $table->getResponse();
        }
        return $this->render('datatable.html.twig', [
            'datatable' => $table,
            'title' => 'Seasons',
            'subtitle' => self::createIconLink(
                'docs',
                'https://docs.pbs.org/display/CDA/Seasons'
            )
        ]);
    }

    /**
     * @Route("/media-manager/seasons/{id}", name="media_manager_seasons_season")
     * @Security("is_granted('ROLE_USER')")
     *
     * @param string $id
     * @param EntityManagerInterface $entityManager
     *
     * @return Response
     */
    public function season($id, EntityManagerInterface $entityManager) {
        $entity = $entityManager
            ->getRepository(Entity\Season::class)
            ->findEager($id);
        if (!$entity) {
            throw new NotFoundHttpException();
        }
        return $this->render('media_manager/season.html.twig', [
            'entity' => $entity,
            'type' => $entity::NAME,
        ]);
    }

    /**
     * @Route("/media-manager/episodes", name="media_manager_episodes")
     * @Security("is_granted('ROLE_USER')")
     *
     * @param DataTableFactory $dataTableFactory
     * @param Request $request
     *
     * @return Response
     */
    public function episodes(DataTableFactory $dataTableFactory, Request $request) {
        $table = $dataTableFactory->createFromType(DataTableType\EpisodesTableType::class)
            ->handleRequest($request);
        if ($table->isCallback()) {
            return $table->getResponse();
        }
        return $this->render('datatable.html.twig', [
            'datatable' => $table,
            'title' => 'Episodes',
            'subtitle' => self::createIconLink(
                'docs',
                'https://docs.pbs.org/display/CDA/Episodes'
            )
        ]);
    }

    /**
     * @Route("/media-manager/episodes/{id}", name="media_manager_episodes_episode")
     * @Security("is_granted('ROLE_USER')")
     *
     * @param string $id
     * @param EntityManagerInterface $entityManager
     *
     * @return Response
     */
    public function episode($id, EntityManagerInterface $entityManager) {
        $entity = $entityManager
            ->getRepository(Entity\Episode::class)
            ->findEager($id);
        if (!$entity) {
            throw new NotFoundHttpException();
        }
        return $this->render('media_manager/episode.html.twig', [
            'entity' => $entity,
            'type' => $entity::NAME,
        ]);
    }

    /**
     * @Route("/media-manager/episodes/{episodeId}/update", name="media_manager_episodes_episode_update")
     * @Security("is_granted('ROLE_ADMIN')")
     *
     * @param string $episodeId
     * @param MediaManagerApiClient $apiClient
     * @param EntityManagerInterface $entityManager
     *
     * @return RedirectResponse
     */
    public function episode_update($episodeId,
                                   MediaManagerApiClient $apiClient,
                                   EntityManagerInterface $entityManager) {
        if (!$apiClient->isConfigured()) {
            throw new NotFoundHttpException(self::$notConfigured);
        }

        /** @var Entity\Episode $episode */
        $episode = $entityManager
            ->getRepository(Entity\Episode::class)
            ->find($episodeId);
        if (!$episode) {
            throw new NotFoundHttpException('Episode not found.');
        }

        $stats = $apiClient->updateAssetsByEpisode($episode);
        $this->flashUpdateStats($stats);
        return $this->redirectToRoute('media_manager_episodes_episode', [
            'id' => $episode->getId()
        ]);
    }

    /**
     * @Route("/media-manager/topics", name="media_manager_topics")
     * @Security("is_granted('ROLE_USER')")
     *
     * @param DataTableFactory $dataTableFactory
     * @param Request $request
     *
     * @return Response
     */
    public function topics(DataTableFactory $dataTableFactory, Request $request) {
        $table = $dataTableFactory->createFromType(DataTableType\TopicsTableType::class)
            ->handleRequest($request);
        if ($table->isCallback()) {
            return $table->getResponse();
        }
        return $this->render('datatable.html.twig', [
            'datatable' => $table,
            'title' => 'Topics',
            'update_route' => 'media_manager_topics_update',
            'subtitle' => self::createIconLink(
                'docs',
                'https://docs.pbs.org/display/CDA/Topics'
            )
        ]);
    }

    /**
     * @Route("/media-manager/topics/update", name="media_manager_topics_update")
     * @Security("is_granted('ROLE_ADMIN')")
     *
     * @param MediaManagerApiClient $apiClient
     *
     * @return RedirectResponse
     */
    public function topics_update(MediaManagerApiClient $apiClient) {
        if (!$apiClient->isConfigured()) {
            throw new NotFoundHttpException(self::$notConfigured);
        }
        $stats = $apiClient->updateAllByEntityClass(Entity\Topic::class);
        $this->flashUpdateStats($stats);
        return $this->redirectToRoute('media_manager_topics');
    }

    /**
     * @Route("/media-manager/topics/{id}", name="media_manager_topics_topic")
     * @Security("is_granted('ROLE_USER')")
     *
     * @param string $id
     * @param EntityManagerInterface $entityManager
     *
     * @return Response
     */
    public function topic($id, EntityManagerInterface $entityManager) {
        $entity = $entityManager
            ->getRepository(Entity\Topic::class)
            ->findEager($id);
        if (!$entity) {
            throw new NotFoundHttpException();
        }
        return $this->render('media_manager/topic.html.twig', [
            'entity' => $entity,
            'type' => $entity::NAME,
        ]);
    }

    /**
     * @Route("/media-manager/assets", name="media_manager_assets")
     * @Security("is_granted('ROLE_USER')")
     *
     * @param DataTableFactory $dataTableFactory
     * @param Request $request
     *
     * @return Response
     */
    public function assets(DataTableFactory $dataTableFactory, Request $request) {
        $table = $dataTableFactory->createFromType(DataTableType\AssetsTableType::class)
            ->handleRequest($request);
        if ($table->isCallback()) {
            return $table->getResponse();
        }
        return $this->render('datatable.html.twig', [
            'datatable' => $table,
            'title' => 'Assets',
            'subtitle' => self::createIconLink(
                'docs',
                'https://docs.pbs.org/display/CDA/Assets'
            )
        ]);
    }

    /**
     * @Route("/media-manager/assets/{id}", name="media_manager_assets_asset")
     * @Security("is_granted('ROLE_USER')")
     *
     * @param string $id
     * @param EntityManagerInterface $entityManager
     *
     * @return Response
     */
    public function asset($id, EntityManagerInterface $entityManager) {
        $entity = $entityManager
            ->getRepository(Entity\Asset::class)
            ->findEager($id);
        if (!$entity) {
            throw new NotFoundHttpException();
        }
        return $this->render('media_manager/asset.html.twig', [
            'entity' => $entity,
            'type' => $entity::NAME,
        ]);
    }

    /**
     * @Route("/media-manager/images", name="media_manager_images")
     * @Security("is_granted('ROLE_USER')")
     *
     * @param DataTableFactory $dataTableFactory
     * @param Request $request
     *
     * @return Response
     */
    public function images(DataTableFactory $dataTableFactory, Request $request) {
        $table = $dataTableFactory->createFromType(DataTableType\ImagesTableType::class)
            ->handleRequest($request);
        if ($table->isCallback()) {
            return $table->getResponse();
        }
        return $this->render('datatable.html.twig', [
            'datatable' => $table,
            'title' => 'Images',
            'subtitle' => self::createIconLink(
                'docs',
                'https://docs.pbs.org/display/CDA/Image+and+Text+Specs'
            )
        ]);
    }

    /**
     * @Route("/media-manager/images/{id}", name="media_manager_images_image")
     * @Security("is_granted('ROLE_USER')")
     *
     * @param string $id
     * @param EntityManagerInterface $entityManager
     *
     * @return Response
     */
    public function image($id, EntityManagerInterface $entityManager) {
        $entity = $entityManager
            ->getRepository(Entity\Image::class)
            ->findEager($id);
        if (!$entity) {
            throw new NotFoundHttpException();
        }
        return $this->render('media_manager/image.html.twig', [
            'entity' => $entity,
            'type' => $entity::NAME,
        ]);
    }

    /**
     * @Route("/media-manager/changelog", name="media_manager_changelog")
     * @Security("is_granted('ROLE_USER')")
     *
     * @param DataTableFactory $dataTableFactory
     * @param Request $request
     *
     * @return Response
     */
    public function changelog(DataTableFactory $dataTableFactory, Request $request) {
        $table = $dataTableFactory->createFromType(DataTableType\ChangelogTableType::class)
            ->handleRequest($request);
        if ($table->isCallback()) {
            return $table->getResponse();
        }
        return $this->render('datatable.html.twig', [
            'datatable' => $table,
            'title' => 'Changelog',
            'update_route' => 'media_manager_changelog_update',
            'subtitle' => self::createIconLink(
                'docs',
                'https://docs.pbs.org/display/CDA/Changelog+Endpoint'
            )
        ]);
    }

    /**
     * @Route("/media-manager/changelog/update", name="media_manager_changelog_update")
     * @Security("is_granted('ROLE_ADMIN')")
     *
     * @param MediaManagerApiClient $apiClient
     *
     * @return RedirectResponse
     *
     * @throws \Exception
     */
    public function changelog_update(MediaManagerApiClient $apiClient) {
        if (!$apiClient->isConfigured()) {
            throw new NotFoundHttpException(self::$notConfigured);
        }
        $stats = $apiClient->updateChangelog();
        $this->flashUpdateStats($stats);
        return $this->redirectToRoute('media_manager_changelog');
    }

    /**
     * @Route("/media-manager/changelog/{id}", name="media_manager_changelog_entry")
     * @Security("is_granted('ROLE_USER')")
     *
     * @param string $id
     * @param EntityManagerInterface $entityManager
     *
     * @return Response
     */
    public function changelog_entry($id, EntityManagerInterface $entityManager) {
        $entity = $entityManager
            ->getRepository(Entity\ChangelogEntry::class)
            ->findEager($id);
        if (!$entity) {
            throw new NotFoundHttpException();
        }
        return $this->render('media_manager/changelog_entry.html.twig', [
            'entity' => $entity,
            'type' => $entity::NAME,
        ]);
    }
}
