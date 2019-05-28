<?php

namespace CascadePublicMedia\PbsApiExplorer\Controller;

use CascadePublicMedia\PbsApiExplorer\DataTable\Type as DataTableType;
use CascadePublicMedia\PbsApiExplorer\Entity\Headend;
use CascadePublicMedia\PbsApiExplorer\Entity\Listing;
use CascadePublicMedia\PbsApiExplorer\Entity\ScheduleProgram;
use CascadePublicMedia\PbsApiExplorer\Service\TvssApiClient;
use Doctrine\ORM\EntityManagerInterface;
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
        $table = $dataTableFactory->createFromType(DataTableType\ProgramsTableType::class)
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
     * @Route("/tvss/programs/{id}", name="tvss_programs_program")
     * @Security("is_granted('ROLE_USER')")
     *
     * @param string $id
     * @param EntityManagerInterface $entityManager
     *
     * @return Response
     */
    public function program($id, EntityManagerInterface $entityManager) {
        $entity = $entityManager
            ->getRepository(ScheduleProgram::class)
            ->findEager($id);
        if (!$entity) {
            throw new NotFoundHttpException();
        }
        return $this->render('tvss/program.html.twig', [
            'entity' => $entity,
            'type' => $entity::NAME,
        ]);
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
        $table = $dataTableFactory->createFromType(DataTableType\HeadendsTableType::class)
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
     * @Route("/tvss/headends/{id}", name="tvss_headends_headend")
     * @Security("is_granted('ROLE_USER')")
     *
     * @param string $id
     * @param EntityManagerInterface $entityManager
     *
     * @return Response
     */
    public function headend($id, EntityManagerInterface $entityManager) {
        $entity = $entityManager
            ->getRepository(Headend::class)
            ->findEager($id);
        if (!$entity) {
            throw new NotFoundHttpException();
        }
        return $this->render('tvss/headend.html.twig', [
            'entity' => $entity,
            'type' => $entity::NAME,
        ]);
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
        $table = $dataTableFactory->createFromType(DataTableType\ListingsTableType::class)
            ->handleRequest($request);
        if ($table->isCallback()) {
            return $table->getResponse();
        }
        return $this->render('tvss/listings.html.twig', [
            'datatable' => $table,
            'title' => 'Listings',
            'subtitle' => self::createIconLink(
                'docs',
                'https://docs.pbs.org/display/tvsapi/TV+Schedules+Service+(TVSS)+API'
            ),
        ]);
    }

    /**
     * @Route("/tvss/listings/update/date/{date}", name="tvss_listings_update_date")
     * @Security("is_granted('ROLE_ADMIN')")
     *
     * @param string $date
     *   Date in the format YYYYMMDD.
     * @param TvssApiClient $apiClient
     *
     * @return RedirectResponse
     */
    public function listings_update_date($date, TvssApiClient $apiClient) {
        if (!$apiClient->isConfigured()) {
            throw new NotFoundHttpException(self::$notConfigured);
        }
        $stats = $apiClient->updateListings($date);
        $this->flashUpdateStats($stats);
        return $this->redirectToRoute('tvss_listings');
    }

    /**
     * @Route("/tvss/listings/update/month/{month}", name="tvss_listings_update_month")
     * @Security("is_granted('ROLE_ADMIN')")
     *
     * @param string $month
     *   Month designation in the format YYYYMM.
     * @param TvssApiClient $apiClient
     *
     * @return RedirectResponse
     * @throws \Exception
     */
    public function listings_update_month($month, TvssApiClient $apiClient) {
        if (!$apiClient->isConfigured()) {
            throw new NotFoundHttpException(self::$notConfigured);
        }
        $stats = $apiClient->updateListingsByMonth($month);
        $this->flashUpdateStats($stats);
        return $this->redirectToRoute('tvss_listings');
    }

    /**
     * @Route("/tvss/listings/{id}", name="tvss_listings_listing")
     * @Security("is_granted('ROLE_USER')")
     *
     * @param string $id
     * @param EntityManagerInterface $entityManager
     *
     * @return Response
     */
    public function listing($id, EntityManagerInterface $entityManager) {
        $entity = $entityManager
            ->getRepository(Listing::class)
            ->findEager($id);
        if (!$entity) {
            throw new NotFoundHttpException();
        }
        return $this->render('tvss/listing.html.twig', [
            'entity' => $entity,
            'type' => $entity::NAME,
        ]);
    }
}
