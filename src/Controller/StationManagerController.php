<?php

namespace CascadePublicMedia\PbsApiExplorer\Controller;

use CascadePublicMedia\PbsApiExplorer\Entity\Station;
use CascadePublicMedia\PbsApiExplorer\Service\StationManagerApiClient;
use CascadePublicMedia\PbsApiExplorer\Service\StationManagerPublicApiClient;
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
     * @param StationManagerApiClient $apiClient
     * @return Response
     */
    public function stations(StationManagerApiClient $apiClient) {
        return $this->render('entity_dumper.html.twig', [
            'entity_class' => 'Station',
            'entities' => $apiClient->updateAndGetByEntityClass(Station::class),
        ]);
    }

    /**
     * @Route("/station-manager/public/stations", name="station_manager_stations_public")
     * @param StationManagerPublicApiClient $apiClient
     * @return Response
     */
    public function stationsPublic(StationManagerPublicApiClient $apiClient) {
        return $this->render('entity_dumper.html.twig', [
            'entity_class' => 'Station',
            'entities' => $apiClient->updateAndGetByEntityClass(Station::class),
        ]);
    }
}
