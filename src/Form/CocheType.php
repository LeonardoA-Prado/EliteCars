<?php

namespace App\Form;

use App\Entity\Coche;
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
            ->add('images', FileType::class, [
                    'label' => 'Imágenes del coche (máximo 5)',
                    'mapped' => false,
                    'required' => false,
                    'multiple' => true,
                    'constraints' => [
                        new Count([
                            'max' => 5,
                            'maxMessage' => 'Solo se permiten hasta 5 imágenes.'
                        ]),
                        new All([
                            'constraints' => [
                                new File([
                                    'maxSize' => '5M',
                                    'mimeTypes' => [
                                        'image/jpeg',
                                        'image/png',
                                    ],
                                    'mimeTypesMessage' => 'Por favor suba una imagen en formato JPEG o PNG.'
                                ])
                            ]
                        ])
                    ],
                ])
            ->add('marca')
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
            ->add('combustible', ChoiceType::class, [
                'choices' => [
                    'Gasolina' => 'gasolina',
                    'Diésel' => 'diesel',
                    'Eléctrico' => 'electrico',
                    'Híbrido' => 'hibrido',
                ],
                'placeholder' => 'Seleccione una opción',
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
        ]);
    }
}
