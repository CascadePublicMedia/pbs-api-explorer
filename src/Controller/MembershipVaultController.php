<?php

namespace CascadePublicMedia\PbsApiExplorer\Controller;

use CascadePublicMedia\PbsApiExplorer\DataTable\Type as DataTableType;
use CascadePublicMedia\PbsApiExplorer\Service\MembershipVaultApiClient;
use Omines\DataTablesBundle\DataTableFactory;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class MembershipVaultController
 *
 * @package CascadePublicMedia\PbsApiExplorer\Controller
 */
class MembershipVaultController extends ControllerBase
{
    private static $notConfigured = 'The Membership Vault API has not been configured. Visit Settings to configure it.';

    /**
     * @Route("/membership-vault", name="mvault")
     * @Security("is_granted('ROLE_USER')")
     */
    public function index()
    {
        return $this->render('default/index.html.twig');
    }

    /**
     * @Route("/membership-vault/memberships", name="mvault_memberships")
     * @Security("is_granted('ROLE_USER')")
     *
     *
     * @param DataTableFactory $dataTableFactory
     * @param Request $request
     *
     * @return Response
     */
    public function memberships(DataTableFactory $dataTableFactory, Request $request)
    {
        $table = $dataTableFactory->createFromType(DataTableType\MembershipsTableType::class)
            ->handleRequest($request);
        if ($table->isCallback()) {
            return $table->getResponse();
        }
        return $this->render('datatable.html.twig', [
            'datatable' => $table,
            'title' => 'Memberships',
        ]);
    }

    /**
     * @Route("/membership-vault/profiles", name="mvault_profiles")
     * @Security("is_granted('ROLE_USER')")
     *
     *
     * @param DataTableFactory $dataTableFactory
     * @param Request $request
     *
     * @return Response
     */
    public function profiles(DataTableFactory $dataTableFactory, Request $request)
    {
        $table = $dataTableFactory->createFromType(DataTableType\PbsProfilesTableType::class)
            ->handleRequest($request);
        if ($table->isCallback()) {
            return $table->getResponse();
        }
        return $this->render('datatable.html.twig', [
            'datatable' => $table,
            'title' => 'Profiles',
        ]);
    }

    /**
     * @Route("/membership-vault/memberships/update", name="mvault_memberships_update")
     * @Security("is_granted('ROLE_ADMIN')")
     *
     * @param MembershipVaultApiClient $apiClient
     *
     * @return RedirectResponse
     */
    public function memberships_update(MembershipVaultApiClient $apiClient)
    {
        if (!$apiClient->isConfigured()) {
            throw new NotFoundHttpException(self::$notConfigured);
        }
        $stats = $apiClient->updateMemberships();
        $this->flashUpdateStats($stats);
        return $this->redirectToRoute('mvault');
    }

}
