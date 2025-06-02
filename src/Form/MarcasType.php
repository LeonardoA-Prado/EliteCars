<?php

namespace App\Form;

use App\Entity\Marcas;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class MarcasType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
             ->add('nombre', TextType::class, [
                 'label' => 'Nombre de la Marca',
                 'attr' => [
                     'class' => 'form-control',
                     'placeholder' => 'Ingresa el nombre de la marca'
                 ]
             ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Marcas::class,
        ]);
    }
}
