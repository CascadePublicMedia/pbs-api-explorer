<?php

namespace CascadePublicMedia\PbsApiExplorer\Controller;

use CascadePublicMedia\PbsApiExplorer\Entity\Membership;
use CascadePublicMedia\PbsApiExplorer\Entity\PbsProfile;
use CascadePublicMedia\PbsApiExplorer\Service\MembershipVaultApiClient;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
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
    public function memberships(DataTableFactory $dataTableFactory, Request $request) {
        $table = $dataTableFactory->create()
            ->add('id', TextColumn::class, ['label' => 'ID'])
            ->add('firstName', TextColumn::class, ['label' => 'First'])
            ->add('lastName', TextColumn::class, ['label' => 'Last'])
            ->add('email', TextColumn::class, ['label' => 'Email'])
            ->add('status', TextColumn::class, ['label' => 'Status'])
            ->add('startDate', DateTimeColumn::class, [
                'label' => 'Start',
                'format' => 'Y-m-d',
            ])
            ->add('activationDate', DateTimeColumn::class, [
                'label' => 'Act.',
                'format' => 'Y-m-d',
            ])
            ->add('expireDate', DateTimeColumn::class, [
                'label' => 'Expire',
                'format' => 'Y-m-d',
            ])
            ->add('updateDate', DateTimeColumn::class, ['label' => 'Updated'])
            ->createAdapter(ORMAdapter::class, ['entity' => Membership::class])
            ->addOrderBy('updateDate', DataTable::SORT_DESCENDING)
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
    public function profiles(DataTableFactory $dataTableFactory, Request $request) {
        $table = $dataTableFactory->create()
            ->add('id', TextColumn::class, ['label' => 'ID'])
            ->add('firstName', TextColumn::class, ['label' => 'First'])
            ->add('lastName', TextColumn::class, ['label' => 'Last'])
            ->add('email', TextColumn::class, ['label' => 'Email'])
            ->add('loginProvider', TextColumn::class, ['label' => 'Provider'])
            ->createAdapter(ORMAdapter::class, ['entity' => PbsProfile::class])
            ->addOrderBy('lastName', DataTable::SORT_ASCENDING)
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
    public function memberships_update(MembershipVaultApiClient $apiClient) {
        if (!$apiClient->isConfigured()) {
            throw new NotFoundHttpException(self::$notConfigured);
        }
        $stats = $apiClient->updateMemberships();
        $this->flashUpdateStats($stats);
        return $this->redirectToRoute('mvault');
    }

}
