<?php

namespace CascadePublicMedia\PbsApiExplorer\Controller;

use CascadePublicMedia\PbsApiExplorer\Entity\ScheduleProgram;
use CascadePublicMedia\PbsApiExplorer\Service\TvssApiClient;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
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
        $this->updateAll(
            $apiClient,
            ScheduleProgram::class,
            ['dataKey' => 'programs']
        );
        return $this->redirectToRoute('tvss_programs');
    }
}
