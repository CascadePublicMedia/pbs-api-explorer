<?php

namespace CascadePublicMedia\PbsApiExplorer\Controller;

use CascadePublicMedia\PbsApiExplorer\DataTable\Type as DataTableType;
use CascadePublicMedia\PbsApiExplorer\Entity\Station;
use CascadePublicMedia\PbsApiExplorer\Service\StationManagerApiClient;
use CascadePublicMedia\PbsApiExplorer\Service\StationManagerPublicApiClient;
use Doctrine\ORM\EntityManagerInterface;
use Omines\DataTablesBundle\DataTableFactory;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class StationManagerController
 *
 * @package CascadePublicMedia\PbsApiExplorer\Controller
 */
class StationManagerController extends ControllerBase
{
    private static $notConfigured = 'The Station Manager API has not been configured. Visit Settings to configure it.';

    /**
     * @Route("/station-manager", name="station_manager")
     * @Security("is_granted('ROLE_USER')")
     */
    public function index()
    {
        return $this->render('station_manager/index.html.twig', [
            'controller_name' => 'StationManagerController',
        ]);
    }

    /**
     * @Route("/station-manager/stations", name="station_manager_stations")
     * @Security("is_granted('ROLE_USER')")
     *
     * @param DataTableFactory $dataTableFactory
     * @param Request $request
     *
     * @return Response
     */
    public function stations(DataTableFactory $dataTableFactory, Request $request)
    {
        $table = $dataTableFactory->createFromType(DataTableType\StationsTableType::class)
            ->handleRequest($request);
        if ($table->isCallback()) {
            return $table->getResponse();
        }
        return $this->render('station_manager/datatable.html.twig', [
            'datatable' => $table,
            'title' => 'Stations',
            'update_route' => '#',  // @TODO Remove this requirement.
            'update_route_internal' => 'station_manager_stations_update',
            'update_route_public' => 'station_manager_stations_public_update',
        ]);
    }

    /**
     * @Route("/station-manager/stations/update", name="station_manager_stations_update")
     * @Security("is_granted('ROLE_ADMIN')")
     *
     * @param StationManagerApiClient $apiClient
     *
     * @return RedirectResponse
     */
    public function stations_update(StationManagerApiClient $apiClient)
    {
        if (!$apiClient->isConfigured()) {
            throw new NotFoundHttpException(self::$notConfigured);
        }
        $stats = $apiClient->updateAllByEntityClass(Station::class);
        $this->flashUpdateStats($stats);
        return $this->redirectToRoute('station_manager_stations');
    }

    /**
     * @Route("/station-manager/stations/public/update", name="station_manager_stations_public_update")
     * @Security("is_granted('ROLE_ADMIN')")
     *
     * @param StationManagerPublicApiClient $apiClient
     *
     * @return RedirectResponse
     */
    public function stations_public_update(StationManagerPublicApiClient $apiClient)
    {
        $stats = $apiClient->updateAllByEntityClass(Station::class);
        $this->flashUpdateStats($stats);
        return $this->redirectToRoute('station_manager_stations');
    }

    /**
     * @Route("/media-manager/stations/{id}", name="station_manager_stations_station")
     * @Security("is_granted('ROLE_USER')")
     *
     * @param string $id
     * @param EntityManagerInterface $entityManager
     *
     * @return Response
     */
    public function station($id, EntityManagerInterface $entityManager) {
        $entity = $entityManager
            ->getRepository(Station::class)
            ->findEager($id);
        if (!$entity) {
            throw new NotFoundHttpException();
        }
        return $this->render('station_manager/station.html.twig', [
            'entity' => $entity,
            'type' => $entity::NAME,
        ]);
    }
}
