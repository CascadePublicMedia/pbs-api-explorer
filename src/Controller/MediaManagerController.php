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
        $show = $entityManager
            ->getRepository(Entity\Show::class)
            ->find($id);

        if (!$show) {
            throw new NotFoundHttpException();
        }

        return $this->render('media_manager/show.html.twig', [
            'show' => $show,
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
        ]);
    }

    /**
     * @Route("/media-manager/episodes/{showId}/update", name="media_manager_episodes_update")
     * @Security("is_granted('ROLE_ADMIN')")
     *
     * @param string $showId
     * @param MediaManagerApiClient $apiClient
     *
     * @return RedirectResponse
     */
    public function episodes_update($showId, MediaManagerApiClient $apiClient) {
        if (!$apiClient->isConfigured()) {
            throw new NotFoundHttpException(self::$notConfigured);
        }
        $apiClient->updateEpisodesByShowId($showId);
        return $this->redirectToRoute('media_manager_shows_show', [
            'id' => $showId
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
}
