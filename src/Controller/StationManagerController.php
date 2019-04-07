<?php

namespace CascadePublicMedia\PbsApiExplorer\Controller;

use CascadePublicMedia\PbsApiExplorer\Entity\Station;
use CascadePublicMedia\PbsApiExplorer\Service\StationManagerApiClient;
use CascadePublicMedia\PbsApiExplorer\Service\StationManagerPublicApiClient;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class StationManagerController
 *
 * @package CascadePublicMedia\PbsApiExplorer\Controller
 */
class StationManagerController extends AbstractController
{
    /**
     * @Route("/station-manager", name="station_manager")
     */
    public function index()
    {
        return $this->render('station_manager/index.html.twig', [
            'controller_name' => 'StationManagerController',
        ]);
    }

    /**
     * @Route("/station-manager/stations", name="station_manager_stations")
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function stations(EntityManagerInterface $entityManager) {
        $entities = $entityManager->getRepository(Station::class)->findAll();
        return $this->render('datatable.html.twig', [
            'properties' => [
                'fullCommonName' => 'Name',
                'shortCommonName' => 'Name (short)',
                'callSign' => 'Call sign',
                'pdp' => 'PDP',
                'passportEnabled' => 'Passport',
                'updated' => 'Updated',
            ],
            'entities' => $entities,
        ]);
    }

    /**
     * @Route("/station-manager/stations/public", name="station_manager_stations_public")
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function stations_public(EntityManagerInterface $entityManager) {
        $entities = $entityManager->getRepository(Station::class)->findAll();
        return $this->render('datatable.html.twig', [
            'properties' => [
                'fullCommonName' => 'Name',
                'shortCommonName' => 'Name (short)',
                'callSign' => 'Call sign',
                'updated' => 'Updated',
            ],
            'entities' => $entities,
        ]);
    }
}
