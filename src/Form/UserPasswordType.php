<?php

namespace CascadePublicMedia\PbsApiExplorer\Form;

use CascadePublicMedia\PbsApiExplorer\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $builder->create(
            'user',
            FormType::class,
            [
                'inherit_data' => TRUE,
                'label' => 'Password',
                'required' => FALSE,
            ])
            ->add('plainPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'first_options'  => array('label' => 'New Password'),
                'second_options' => array('label' => 'Repeat New Password'),
            ))
        ;

        $builder
            ->add($user)
            //->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => User::class,
        ));
    }
}
