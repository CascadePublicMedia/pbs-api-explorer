<?php

namespace CascadePublicMedia\PbsApiExplorer\Controller;

use CascadePublicMedia\PbsApiExplorer\Entity\Setting;
use CascadePublicMedia\PbsApiExplorer\Form\SettingsType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SettingsController
 *
 * @package CascadePublicMedia\PbsApiExplorer\Controller
 */
class SettingsController extends AbstractController
{
    /**
     * App settings.
     *
     * @Route("/settings", name="settings")
     * @Security("is_granted('ROLE_ADMIN')")
     *
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     *
     * @return Response
     *
     * @throws \Exception
     */
    public function settings(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Get all current Setting values for default field data.
        $settings = $entityManager
            ->getRepository(Setting::class)
            ->findAllIndexedById();

        $defaults = [];
        /** @var Setting $setting */
        foreach ($settings as $setting) {
            $defaults[$setting->getId()] = $setting->getValue();
        }

        // Create form.
        $form = $this->createForm(SettingsType::class, $defaults);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            // Update/add Setting values as needed.
            foreach ($data as $id => $value) {
                if (!isset($settings[$id])) {
                    $setting = new Setting();
                    $setting->setId($id);
                }
                else {
                    $setting = $settings[$id];
                }

                $setting->setValue($value);
                $setting->setOwner($this->getUser());
                $setting->setUpdated(new \DateTime());

                $entityManager->persist($setting);
            }

            $entityManager->flush();
        }

        return $this->render('settings/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
