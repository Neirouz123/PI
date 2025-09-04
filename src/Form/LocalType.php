<?php

namespace App\Form;

use App\Entity\Local;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LocalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Venue Name',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Enter venue name...',
                    'maxlength' => 255
                ]
            ])
            ->add('adresse', TextType::class, [
                'label' => 'Address',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Enter full address...',
                    'maxlength' => 255
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Enter venue description...',
                    'rows' => 4,
                    'maxlength' => 1000
                ]
            ])
            ->add('capacite', IntegerType::class, [
                'label' => 'Capacity',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Maximum number of people...',
                    'min' => 1,
                    'max' => 10000
                ]
            ])
            ->add('prix', MoneyType::class, [
                'label' => 'Price per Hour',
                'currency' => 'EUR',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => '0.00',
                    'min' => 0,
                    'step' => 0.01
                ]
            ])
            ->add('disponible', CheckboxType::class, [
                'label' => 'Available for booking',
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
            'data_class' => Local::class,
        ]);
    }
}
