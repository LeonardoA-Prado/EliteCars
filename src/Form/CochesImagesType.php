<?php

namespace App\Form;

use App\Entity\Coche;
use App\Entity\CochesImages;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;

class CochesImagesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('imageFile', FileType::class, [
                'mapped' => false,
                'required' => false,
                'label' => 'Cambiar Imagen',
                'constraints' => [
                    new File([
                        'maxSize' => '5M',
                        'maxSizeMessage' => 'La imagen no debe superar los 5MB.',
                        'mimeTypes' => ['image/jpeg', 'image/png'],
                        'mimeTypesMessage' => 'Por favor sube un archivo de imagen válido (JPG o PNG).'
                    ])
                ]
            ])
            ->add('posicion')
            ->add('delete', CheckboxType::class, [
                'mapped' => false,
                'required' => false,
                'label' => 'Eliminar',
            ])
        ;
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CochesImages::class,
            'is_admin' => false,
        ]);
    }
}
