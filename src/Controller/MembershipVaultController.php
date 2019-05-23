<?php

namespace CascadePublicMedia\PbsApiExplorer\Controller;

use CascadePublicMedia\PbsApiExplorer\DataTable\Type as DataTableType;
use CascadePublicMedia\PbsApiExplorer\Entity\Membership;
use CascadePublicMedia\PbsApiExplorer\Entity\PbsProfile;
use CascadePublicMedia\PbsApiExplorer\Service\MembershipVaultApiClient;
use Doctrine\ORM\EntityManagerInterface;
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
            'subtitle' => self::createIconLink(
                'docs',
                'https://docs.pbs.org/display/uua/Integrating+PBS+Account+with+your+website+or+app'
            ),
        ]);
    }

    /**
     * @Route("/membership-vault/profiles/{id}", name="mvault_profiles_profile")
     * @Security("is_granted('ROLE_USER')")
     *
     * @param string $id
     * @param EntityManagerInterface $entityManager
     *
     * @return Response
     */
    public function profile($id, EntityManagerInterface $entityManager) {
        $entity = $entityManager
            ->getRepository(PbsProfile::class)
            ->findEager($id);
        if (!$entity) {
            throw new NotFoundHttpException();
        }
        return $this->render('mvault/profile.html.twig', [
            'entity' => $entity,
            'type' => $entity::NAME,
        ]);
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
            'subtitle' => self::createIconLink(
                'docs',
                'https://docs.pbs.org/display/MV/Membership+Vault+API'
            ),
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

    /**
     * @Route("/membership-vault/memberships/{id}", name="mvault_memberships_membership")
     * @Security("is_granted('ROLE_USER')")
     *
     * @param string $id
     * @param EntityManagerInterface $entityManager
     *
     * @return Response
     */
    public function membership($id, EntityManagerInterface $entityManager) {
        $entity = $entityManager
            ->getRepository(Membership::class)
            ->findEager($id);
        if (!$entity) {
            throw new NotFoundHttpException();
        }
        return $this->render('mvault/membership.html.twig', [
            'entity' => $entity,
            'type' => $entity::NAME,
        ]);
    }

}
