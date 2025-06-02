<?php

namespace App\Form;

use App\Entity\Coche;
use App\Form\CochesImagesType;
use App\Entity\Marcas;
use App\Entity\Combustible;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\File;

class CocheType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('cochesImages', CollectionType::class, [
                'entry_type' => CochesImagesType::class,
                'allow_add' => true,
                'entry_options' => ['is_admin' => $options['is_admin']],
                'allow_delete' => true,
                'by_reference' => false,
                'prototype' => true,
                'constraints' => [
                    new Count([
                        'max' => 10,
                        'maxMessage' => 'No se pueden subir más de 10 imágenes.'
                    ])
                ]
            ])
            ->add('marca', EntityType::class, [
                'class' => Marcas::class,
                'choice_label' => 'nombre',
                'placeholder' => 'Seleccione una marca',
                'attr' => ['class' => 'form-control']
            ])
            ->add('modelo')
            ->add('version')
            ->add('precio')
            ->add('kilometros')
            ->add('ciudad')
            ->add('carroceria', ChoiceType::class, [
                'choices' => [
                    'Berlina' => 'berlina',
                    'Pickup' => 'pickup',
                    'SUV' => 'suv',
                ],
                'placeholder' => 'Seleccione una opción',
            ])
            ->add('color')
            ->add('cambio')
            ->add('combustible', EntityType::class, [
                'class' => Combustible::class,
                'choice_label' => 'nombre',
                'placeholder' => 'Seleccione una marca',
                'attr' => ['class' => 'form-control']
            ])
            ->add('traccion')
            ->add('potencia')
            ->add('cilindrada')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Coche::class,
             'is_admin' => false,
        ]);
    }
}
