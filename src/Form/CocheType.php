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
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints as Assert;

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
                        'min' => 1,
                        'max' => 10,
                        'maxMessage' => 'No se pueden subir más de 10 imágenes.',
                        'minMessage' => 'debes subir al menos 1 imagen.'
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
            ->add('precio',IntegerType::class, [
                    'constraints' => [
                        new Assert\PositiveOrZero([
                            'message' => 'El precio no puede ser negativo.',
                        ]),
                        new Assert\Range([
                            'max' => 10000000,
                            'maxMessage' => 'El Precio no puede ser mayor a {{ limit }}.',
                        ]),
                    ],
                    'attr' => ['class' => 'form-control', 'placeholder' => 'Kilómetros'],
                ])
            ->add('kilometros', IntegerType::class, [
                    'constraints' => [
                        new Assert\PositiveOrZero([
                            'message' => 'El kilometraje no puede ser negativo.',
                        ]),
                        new Assert\Range([
                            'max' => 1000000,
                            'maxMessage' => 'El kilometraje no puede ser mayor a {{ limit }} km.',
                        ]),
                    ],
                    'attr' => ['class' => 'form-control', 'placeholder' => 'Kilómetros'],
                ])
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
            ->add('cambio',ChoiceType::class,[
                'choices' => [
                    'Manual' => 'manual',
                    'Automatico' => 'automatico',
                ],
                'placeholder' => 'Seleccione una opción',
            ])
            ->add('combustible', EntityType::class, [
                'class' => Combustible::class,
                'choice_label' => 'nombre',
                'placeholder' => 'Seleccione una marca',
                'attr' => ['class' => 'form-control']
            ])
            ->add('traccion',ChoiceType::class,[
                'choices' => [
                    'Trasera' => 'trasera',
                    'Delantera' => 'delantera',
                    '4x4' => '4x4',
                ],
                'placeholder' => 'Seleccione una opción',
            ])
            ->add('potencia', null, [
                'constraints' => [
                    new Range([
                        'min' => 60,
                        'max' => 2000,
                        'notInRangeMessage' => 'La potencia debe estar entre {{ min }} y {{ max }} CV.',
                    ])
                ],
                'attr' => ['class' => 'form-control', 'placeholder' => 'Ej: 150']
            ])
            ->add('cilindrada', null, [
                'constraints' => [
                    new Range([
                        'min' => 500,
                        'max' => 8000,
                        'notInRangeMessage' => 'La cilindrada debe estar entre {{ min }} y {{ max }} CC.',
                    ])
                ],
                'attr' => ['class' => 'form-control', 'placeholder' => 'Ej: 800']
            ])
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
