<?php

namespace CascadePublicMedia\PbsApiExplorer\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Url;

/**
 * Class SettingsType
 *
 * @package CascadePublicMedia\PbsApiExplorer\Form
 */
class SettingsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $media_manager = $builder->create(
            'media_manager',
            FormType::class,
            [
                'inherit_data' => TRUE,
                'label' => 'Media Manager',
                'required' => FALSE,
            ])
            ->add(
                'media_manager_base_uri',
                TextType::class,
                [
                    'constraints' => [
                        new Url(),
                    ],
                    'label' => 'Endpoint',
                    'required' => FALSE,
                ])
            ->add(
                'media_manager_client_id',
                TextType::class,
                [
                    'label' => 'Client ID',
                    'required' => FALSE,
                ])
            ->add(
                'media_manager_client_secret',
                TextType::class,
                [
                    'label' => 'Client secret',
                    'required' => FALSE,
                ])
            ;

        $station_manager = $builder->create(
            'station_manager',
            FormType::class,
            [
                'inherit_data' => TRUE,
                'label' => 'Station Manager',
                'required' => FALSE,
            ])
            ->add(
                'station_manager_base_uri',
                TextType::class,
                [
                    'constraints' => [
                        new Url(),
                    ],
                    'label' => 'Endpoint',
                    'required' => FALSE,
                ])
            ->add(
                'station_manager_client_id',
                TextType::class,
                [
                    'label' => 'Client ID',
                    'required' => FALSE,
                ])
            ->add(
                'station_manager_client_secret',
                TextType::class,
                [
                    'label' => 'Client secret',
                    'required' => FALSE,
                ])
        ;

        $tvss = $builder->create(
            'tvss',
            FormType::class,
            [
                'inherit_data' => TRUE,
                'label' => 'TV Schedules Service (TVSS)',
                'required' => FALSE,
            ])
            ->add(
                'tvss_base_uri',
                TextType::class,
                [
                    'constraints' => [
                        new Url(),
                    ],
                    'label' => 'Endpoint',
                    'required' => FALSE,
                ])
            ->add(
                'tvss_call_sign',
                TextType::class,
                [
                    'label' => 'Call sign',
                    'required' => FALSE,
                ])
            ->add(
                'tvss_api_key',
                TextType::class,
                [
                    'label' => 'API key',
                    'required' => FALSE,
                ])
        ;

        $mvault = $builder->create(
            'mvault',
            FormType::class,
            [
                'inherit_data' => TRUE,
                'label' => 'Membership Vault',
                'required' => FALSE,
            ])
            ->add(
                'mvault_base_uri',
                TextType::class,
                [
                    'constraints' => [
                        new Url(),
                    ],
                    'label' => 'Endpoint',
                    'required' => FALSE,
                ])
            ->add(
                'mvault_client_id',
                TextType::class,
                [
                    'label' => 'Client ID',
                    'required' => FALSE,
                ])
            ->add(
                'mvault_client_secret',
                TextType::class,
                [
                    'label' => 'Client secret',
                    'required' => FALSE,
                ])
        ;

        $builder
            ->add($media_manager)
            ->add($station_manager)
            ->add($tvss)
            ->add('save', SubmitType::class)
        ;
    }
}
