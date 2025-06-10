<?php

namespace App\Form;

use App\Entity\Combustible;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CombustibleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
             ->add('nombre', TextType::class, [
                 'label' => 'Tipo de Combustible',
                 'attr' => [
                     'class' => 'form-control',
                     'placeholder' => 'Ingresa el tipo de combustible'
                 ]
             ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Combustible::class,
        ]);
    }
}
