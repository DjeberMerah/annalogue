<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'placeholder' => 'Nom de l\'utilisateur'
                ]
            ]);

        if ($options['mode'] === 'CREATE') {
            $builder
                ->add('mail', EmailType::class, [
                    'label' => 'Adresse email',
                    'attr' => [
                        'placeholder' => 'Adresse email de l\'utilisateur'
                    ]
                ])
                ->add('password', RepeatedType::class, [
                    'type' => PasswordType::class,
                    'first_options' => [
                        'label' => 'Mot de passe',
                        'attr' => [
                            'placeholder' => '****'
                        ]
                    ],
                    'second_options' => [
                        'label' => 'Confirmer le mot de passe',
                        'attr' => [
                            'placeholder' => '****'
                        ]
                    ],
                ]);
        }

        $builder
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Utilisateur' => 'ROLE_USER',
                    'Responsable' => 'ROLE_RESP',
                    'Administrateur' => 'ROLE_ADMIN'
                ],
                'label' => 'Rôles',
                'multiple' => true
            ]);

        $builder->add('submit', SubmitType::class, [
            'label' => $options['mode'] === 'CREATE' ? 'Créer l\'utilisateur' : 'Modifier l\'utilisateur'
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'mode' => 'CREATE'
        ]);
    }
}
