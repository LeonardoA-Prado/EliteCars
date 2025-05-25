<?php

namespace App\Form;

use App\Entity\Coche;
use App\Entity\CochesImages;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CochesImagesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('rutaImagen')
            ->add('posicion')
            ->add('coche_id', EntityType::class, [
                'class' => Coche::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CochesImages::class,
        ]);
    }
}
