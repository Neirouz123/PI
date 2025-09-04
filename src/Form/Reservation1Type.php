<?php

namespace App\Form;

use App\Entity\Local;
use App\Entity\Reservation;
use App\Entity\Utilisateur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Reservation1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date', DateType::class, [
                'label' => 'Reservation Date',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control',
                    'min' => date('Y-m-d')
                ]
            ])
            ->add('heureDebut', TimeType::class, [
                'label' => 'Start Time',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('heureFin', TimeType::class, [
                'label' => 'End Time',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('prix', MoneyType::class, [
                'label' => 'Total Price',
                'currency' => 'EUR',
                'attr' => [
                    'class' => 'form-control',
                    'min' => 0,
                    'step' => 0.01
                ]
            ])
            ->add('local', EntityType::class, [
                'class' => Local::class,
                'choice_label' => 'nom',
                'label' => 'Venue',
                'attr' => [
                    'class' => 'form-select'
                ],
                'placeholder' => 'Select a venue...'
            ])
            ->add('utilisateur', EntityType::class, [
                'class' => Utilisateur::class,
                'choice_label' => 'username',
                'label' => 'Customer',
                'attr' => [
                    'class' => 'form-select'
                ],
                'placeholder' => 'Select a customer...'
            ])
            ->add('statut', ChoiceType::class, [
                'label' => 'Status',
                'choices' => [
                    'Pending' => 'pending',
                    'Confirmed' => 'confirmed',
                    'Cancelled' => 'cancelled',
                    'Completed' => 'completed'
                ],
                'attr' => [
                    'class' => 'form-select'
                ]
            ])
            ->add('isConfirmee', CheckboxType::class, [
                'label' => 'Confirmed',
                'required' => false,
                'attr' => [
                    'class' => 'form-check-input'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
