<?php

namespace CascadePublicMedia\PbsApiExplorer\Controller;

use CascadePublicMedia\PbsApiExplorer\Form\UserPasswordType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * Login handler.
     *
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

    /**
     * Logout handler.
     *
     * This method can't be blank.
     *
     * @see https://symfony.com/doc/current/security.html#logging-out
     *
     * @Route("/logout", name="logout", methods={"GET"})
     */
    public function logout()
    {
        return;
    }

    /**
     * @Route("/user/password", name="user_password")
     * @IsGranted("ROLE_USER")
     *
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     *
     * @return RedirectResponse|Response
     */
    public function user_password(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = $this->getUser();
        $form = $this->createForm(UserPasswordType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success', 'Password updated!');
            return $this->redirectToRoute('home');
        }

        return $this->render(
            'security/user_password.html.twig',
            [
                'user' => $user,
                'form' => $form->createView()
            ]
        );
    }
}
