<?php

namespace CascadePublicMedia\PbsApiExplorer\Controller;

use CascadePublicMedia\PbsApiExplorer\Entity\ScheduleProgram;
use CascadePublicMedia\PbsApiExplorer\Service\TvssApiClient;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
     * @param EntityManagerInterface $entityManager
     *
     * @return Response
     */
    public function programs(EntityManagerInterface $entityManager) {
        $entities = $entityManager
            ->getRepository(ScheduleProgram::class)
            ->findAll();
        return $this->render('datatable.html.twig', [
            'title' => 'Programs',
            'properties' => [
                'programId' => 'ID',
                'title' => 'Title',
                'externalId' => 'External ID',
            ],
            'entities' => $entities,
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
        $this->updateAll(
            $apiClient,
            ScheduleProgram::class,
            ['dataKey' => 'programs']
        );
        return $this->redirectToRoute('tvss_programs');
    }
}
