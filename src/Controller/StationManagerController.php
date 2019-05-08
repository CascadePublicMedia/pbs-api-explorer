<?php

namespace CascadePublicMedia\PbsApiExplorer\Controller;

use CascadePublicMedia\PbsApiExplorer\Entity\Station;
use CascadePublicMedia\PbsApiExplorer\Service\StationManagerApiClient;
use CascadePublicMedia\PbsApiExplorer\Service\StationManagerPublicApiClient;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\BoolColumn;
use Omines\DataTablesBundle\Column\DateTimeColumn;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTable;
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
    public function stations(DataTableFactory $dataTableFactory, Request $request) {
        $table = $dataTableFactory->create()
            ->add('fullCommonName', TextColumn::class, ['label' => 'Name'])
            ->add('shortCommonName', TextColumn::class, ['label' => 'Name (short)'])
            ->add('callSign', TextColumn::class, ['label' => 'Call sign'])
            ->add('pdp', BoolColumn::class, [
                'label' => 'PDP',
                'className' => 'text-center',
                'trueValue' => '<i class="fas fa-check-circle text-green"></i>',
                'falseValue' => '<i class="fas fa-times-circle text-red"></i>',
            ])
            ->add('passportEnabled', BoolColumn::class, [
                'label' => 'Passport',
                'className' => 'text-center',
                'trueValue' => '<i class="fas fa-check-circle text-green"></i>',
                'falseValue' => '<i class="fas fa-times-circle text-red"></i>',
            ])
            ->add('updated', DateTimeColumn::class, ['label' => 'Updated'])
            ->createAdapter(ORMAdapter::class, ['entity' => Station::class])
            ->addOrderBy('fullCommonName', DataTable::SORT_ASCENDING)
            ->handleRequest($request);

        if ($table->isCallback()) {
            return $table->getResponse();
        }

        return $this->render('station_manager/datatable.html.twig', [
            'datatable' => $table,
            'title' => 'Stations',
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
    public function stations_update(StationManagerApiClient $apiClient) {
        if (!$apiClient->isConfigured()) {
            throw new NotFoundHttpException(self::$notConfigured);
        }
        $this->updateAll($apiClient, Station::class);
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
    public function stations_public_update(StationManagerPublicApiClient $apiClient) {
        $this->updateAll($apiClient, Station::class);
        return $this->redirectToRoute('station_manager_stations');
    }
}
