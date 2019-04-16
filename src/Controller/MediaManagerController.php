<?php

namespace CascadePublicMedia\PbsApiExplorer\Controller;

use CascadePublicMedia\PbsApiExplorer\Entity\Franchise;
use CascadePublicMedia\PbsApiExplorer\Entity\Genre;
use CascadePublicMedia\PbsApiExplorer\Entity\Season;
use CascadePublicMedia\PbsApiExplorer\Entity\Show;
use CascadePublicMedia\PbsApiExplorer\Service\MediaManagerApiClient;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
     * @param EntityManagerInterface $entityManager
     *
     * @return Response
     */
    public function genres(EntityManagerInterface $entityManager) {
        $entities = $entityManager->getRepository(Genre::class)->findAll();
        return $this->render('datatable.html.twig', [
            'title' => 'Genres',
            'properties' => [
                'title' => 'Title',
                'slug' => 'Slug',
                'created' => 'Created',
                'updated' => 'Updated',
            ],
            'entities' => $entities,
            'update_route' => 'media_manager_genres_update',
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
        $this->updateAll($apiClient, Genre::class);
        return $this->redirectToRoute('media_manager_genres');
    }

    /**
     * @Route("/media-manager/franchises", name="media_manager_franchises")
     * @Security("is_granted('ROLE_USER')")
     *
     * @param EntityManagerInterface $entityManager
     *
     * @return Response
     */
    public function franchises(EntityManagerInterface $entityManager) {
        $entities = $entityManager->getRepository(Franchise::class)->findAll();
        return $this->render('datatable.html.twig', [
            'title' => 'Franchises',
            'properties' => [
                'title' => 'Title',
                'slug' => 'Slug',
                'genre' => 'Genre',
                'updated' => 'Updated',
            ],
            'entities' => $entities,
            'update_route' => 'media_manager_franchises_update',
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
        $this->updateAll($apiClient, Franchise::class);
        return $this->redirectToRoute('media_manager_franchises');
    }

    /**
     * @Route("/media-manager/shows", name="media_manager_shows")
     * @Security("is_granted('ROLE_USER')")
     *
     * @param EntityManagerInterface $entityManager
     *
     * @return Response
     */
    public function shows(EntityManagerInterface $entityManager) {
        $entities = $entityManager->getRepository(Show::class)->findAll();
        return $this->render('datatable.html.twig', [
            'title' => 'Shows',
            'properties' => [
                'title' => 'Title',
                'slug' => 'Slug',
                'franchise' => 'Franchise',
                'genre' => 'Genre',
                'updated' => 'Updated',
            ],
            'entities' => $entities,
            'entity_route' => 'media_manager_shows_show',
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
        $this->updateAll($apiClient, Show::class);
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
            ->getRepository(Show::class)
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
     * @param EntityManagerInterface $entityManager
     *
     * @return Response
     */
    public function seasons(EntityManagerInterface $entityManager) {
        $entities = $entityManager->getRepository(Season::class)->findAll();
        return $this->render('datatable.html.twig', [
            'title' => 'Seasons',
            'properties' => [
                'show' => 'Show',
                'ordinal' => 'Ordinal',
                'title' => 'Title',
                'updated' => 'Updated',
            ],
            'entities' => $entities,
        ]);
    }
}
