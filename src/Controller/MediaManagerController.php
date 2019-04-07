<?php

namespace CascadePublicMedia\PbsApiExplorer\Controller;

use CascadePublicMedia\PbsApiExplorer\Entity\Franchise;
use CascadePublicMedia\PbsApiExplorer\Entity\Genre;
use CascadePublicMedia\PbsApiExplorer\Entity\Show;
use CascadePublicMedia\PbsApiExplorer\Service\MediaManagerApiClient;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class MediaManagerController
 *
 * @package CascadePublicMedia\PbsApiExplorer\Controller
 */
class MediaManagerController extends AbstractController
{
    /**
     * @Route("/media-manager", name="media_manager")
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
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function genres(EntityManagerInterface $entityManager) {
        $entities = $entityManager->getRepository(Genre::class)->findAll();
        return $this->render('datatable.html.twig', [
            'properties' => [
                'id' => 'ID',
                'title' => 'Title',
                'slug' => 'Slug',
                'created' => 'Created',
                'updated' => 'Updated',
            ],
            'entities' => $entities,
        ]);
    }

    /**
     * @Route("/media-manager/franchises", name="media_manager_franchises")
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function franchises(EntityManagerInterface $entityManager) {
        $entities = $entityManager->getRepository(Franchise::class)->findAll();
        return $this->render('datatable.html.twig', [
            'properties' => [
                'id' => 'ID',
                'title' => 'Title',
                'slug' => 'Slug',
                'genre' => 'Genre',
                'updated' => 'Updated',
            ],
            'entities' => $entities,
        ]);
    }

    /**
     * @Route("/media-manager/shows", name="media_manager_shows")
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function shows(EntityManagerInterface $entityManager) {
        $entities = $entityManager->getRepository(Show::class)->findAll();
        return $this->render('datatable.html.twig', [
            'properties' => [
                'id' => 'ID',
                'title' => 'Title',
                'slug' => 'Slug',
                'franchise' => 'Franchise',
                'genre' => 'Genre',
                'updated' => 'Updated',
            ],
            'entities' => $entities,
        ]);
    }
}
