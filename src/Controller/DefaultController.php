<?php

namespace CascadePublicMedia\PbsApiExplorer\Controller;

use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DefaultController
 *
 * @package CascadePublicMedia\PbsApiExplorer\Controller
 */
class DefaultController extends ControllerBase
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }
}
