<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UtilisateurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'label' => 'Username',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Enter username...',
                    'maxlength' => 255,
                    'pattern' => '[a-zA-Z0-9_]+',
                    'title' => 'Username can only contain letters, numbers and underscores'
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email Address',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Enter email address...',
                    'maxlength' => 255
                ]
            ])
            ->add('password_hash', PasswordType::class, [
                'label' => 'Password',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Enter password...',
                    'minlength' => 6
                ]
            ])
            ->add('role', ChoiceType::class, [
                'label' => 'User Role',
                'choices' => [
                    'Client' => 'CLIENT',
                    'Administrator' => 'ADMIN'
                ],
                'attr' => [
                    'class' => 'form-select'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
