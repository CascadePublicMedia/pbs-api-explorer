<?php

namespace CascadePublicMedia\PbsApiExplorer\Controller;

use CascadePublicMedia\PbsApiExplorer\Entity\Headend;
use CascadePublicMedia\PbsApiExplorer\Entity\Listing;
use CascadePublicMedia\PbsApiExplorer\Entity\ScheduleProgram;
use CascadePublicMedia\PbsApiExplorer\Service\TvssApiClient;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\DateTimeColumn;
use Omines\DataTablesBundle\Column\NumberColumn;
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
 * Class TvssController
 *
 * @package CascadePublicMedia\PbsApiExplorer\Controller
 */
class TvssController extends ControllerBase
{
    private static $notConfigured = 'The TV Schedules Service (TVSS) API has not
        been configured. Visit Settings to configure it.';

    /**
     * @Route("/tvss", name="tvss")
     * @Security("is_granted('ROLE_USER')")
     */
    public function index()
    {
        return $this->render('tvss/index.html.twig', [
            'controller_name' => 'TvssController',
        ]);
    }

    /**
     * @Route("/tvss/programs", name="tvss_programs")
     * @Security("is_granted('ROLE_USER')")
     *
     *
     * @param DataTableFactory $dataTableFactory
     * @param Request $request
     *
     * @return Response
     */
    public function programs(DataTableFactory $dataTableFactory, Request $request) {
        $table = $dataTableFactory->create()
            ->add('programId', TextColumn::class, ['label' => 'ID'])
            ->add('title', TextColumn::class, ['label' => 'Title'])
            ->add('externalId', TextColumn::class, ['label' => 'External ID'])
            ->createAdapter(ORMAdapter::class, ['entity' => ScheduleProgram::class])
            ->addOrderBy('title', DataTable::SORT_ASCENDING)
            ->handleRequest($request);

        if ($table->isCallback()) {
            return $table->getResponse();
        }

        return $this->render('datatable.html.twig', [
            'datatable' => $table,
            'title' => 'Programs',
            'update_route' => 'tvss_programs_update',
        ]);
    }

    /**
     * @Route("/tvss/programs/update", name="tvss_programs_update")
     * @Security("is_granted('ROLE_ADMIN')")
     *
     * @param TvssApiClient $apiClient
     *
     * @return RedirectResponse
     */
    public function programs_update(TvssApiClient $apiClient) {
        if (!$apiClient->isConfigured()) {
            throw new NotFoundHttpException(self::$notConfigured);
        }
        $stats = $apiClient->updatePrograms();
        $this->flashUpdateStats($stats);
        return $this->redirectToRoute('tvss_programs');
    }

    /**
     * @Route("/tvss/headends", name="tvss_headends")
     * @Security("is_granted('ROLE_USER')")
     *
     *
     * @param DataTableFactory $dataTableFactory
     * @param Request $request
     *
     * @return Response
     */
    public function headends(DataTableFactory $dataTableFactory, Request $request) {
        $table = $dataTableFactory->create()
            ->add('id', TextColumn::class, ['label' => 'ID'])
            ->add('name', TextColumn::class, ['label' => 'Name'])
            ->createAdapter(ORMAdapter::class, ['entity' => Headend::class])
            ->addOrderBy('name', DataTable::SORT_ASCENDING)
            ->handleRequest($request);

        if ($table->isCallback()) {
            return $table->getResponse();
        }

        return $this->render('datatable.html.twig', [
            'datatable' => $table,
            'title' => 'Headends',
            'update_route' => 'tvss_headends_update',
        ]);
    }

    /**
     * @Route("/tvss/headends/update", name="tvss_headends_update")
     * @Security("is_granted('ROLE_ADMIN')")
     *
     * @param TvssApiClient $apiClient
     *
     * @return RedirectResponse
     */
    public function headends_update(TvssApiClient $apiClient) {
        if (!$apiClient->isConfigured()) {
            throw new NotFoundHttpException(self::$notConfigured);
        }
        $stats = $apiClient->updateHeadends();
        $this->flashUpdateStats($stats);
        return $this->redirectToRoute('tvss_headends');
    }

    /**
     * @Route("/tvss/listings", name="tvss_listings")
     * @Security("is_granted('ROLE_USER')")
     *
     *
     * @param DataTableFactory $dataTableFactory
     * @param Request $request
     *
     * @return Response
     */
    public function listings(DataTableFactory $dataTableFactory, Request $request) {
        $table = $dataTableFactory->create()
            ->add('feed', TextColumn::class, [
                'field' => 'feed.fullName',
                'label' => 'Feed',
            ])
            ->add('date', DateTimeColumn::class, [
                'label' => 'Date',
                'format' => 'Y-m-d',
            ])
            ->add('startTime', TextColumn::class, ['label' => 'Start time'])
            ->add('durationMinutes', NumberColumn::class, ['label' => 'Duration'])
            ->add('title', TextColumn::class, ['label' => 'Title'])
            ->add('episodeTitle', TextColumn::class, ['label' => 'Episode title'])
            ->createAdapter(ORMAdapter::class, ['entity' => Listing::class])
            ->addOrderBy('feed', DataTable::SORT_DESCENDING)
            ->addOrderBy('date', DataTable::SORT_DESCENDING)
            ->addOrderBy('startTime', DataTable::SORT_ASCENDING)
            ->handleRequest($request);

        if ($table->isCallback()) {
            return $table->getResponse();
        }

        return $this->render('datatable.html.twig', [
            'datatable' => $table,
            'title' => 'Listings',
        ]);
    }

    /**
     * @Route("/tvss/listings/update/{date}", name="tvss_listings_update")
     * @Security("is_granted('ROLE_ADMIN')")
     *
     * @param string $date
     *   Date in the format YYYYMMDD.
     * @param TvssApiClient $apiClient
     *
     * @return RedirectResponse
     */
    public function listings_update($date, TvssApiClient $apiClient) {
        if (!$apiClient->isConfigured()) {
            throw new NotFoundHttpException(self::$notConfigured);
        }
        $stats = $apiClient->updateListings($date);
        $this->flashUpdateStats($stats);
        return $this->redirectToRoute('tvss_listings');
    }
}
