<?php

namespace App\Form;

use App\Entity\Usuario;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UsuarioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre')
            ->add('apellidos')
            ->add('email')
            ->add('contrasena', PasswordType::class, [
                'required' => $options['edit'] ? false : true,
                'mapped' => false, // usando un campo no mapeado para que no se rellene con el hash
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Ingresa tu contraseña'
                ]
            ]);
            if ($options['is_admin']) {
            $builder->add('roles', ChoiceType::class, [
                'choices'  => [
                    'User'   => 'ROLE_USER',
                    'Admin'  => 'ROLE_ADMIN',
                    'Editor' => 'ROLE_EDITOR',
                ],
                'multiple' => true,
                'expanded' => false,
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Usuario::class,
            'is_admin'   => false,
            'edit'       => false
        ]);

        $resolver->setAllowedTypes('is_admin', 'bool');
        $resolver->setAllowedTypes('edit', 'bool');
    }
}
