<?php

namespace CascadePublicMedia\PbsApiExplorer\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     * @IsGranted("IS_AUTHENTICATED_ANONYMOUSLY")
     */
    public function login(AuthenticationUtils $authenticationUtils,
                          CsrfTokenManagerInterface $csrfTokenManager): Response
    {
        $csrf_token = $csrfTokenManager->getToken('login')->getValue();
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'csrf_token' => $csrf_token,
            'error' => $error,
            'last_username' => $lastUsername,
        ]);
    }
}
