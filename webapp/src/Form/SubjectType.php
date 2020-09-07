<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class SubjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'placeholder' => 'Titre du sujet'
                ]
            ])
            ->add('description', TextType::class, [
                'label' => 'Description',
                'attr' => [
                    'placeholder' => 'Description du sujet'
                ],
                'required' => false
            ])
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Cours' => 'Cours',
                    'TD' => 'TD',
                    'TP' => 'TP',
                    'Autre' => 'Autre',
                ],
                'label' => 'Type'
            ]);

        if ($options['mode'] === 'CREATE') {
            $builder->add('document', FileType::class, [
                'label' => 'Document',
                'attr' => [
                    'placeholder' => 'Choisir un fichier...'
                ],
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '4092k',
                        'mimeTypes' => [
                            'application/pdf',
                            'application/x-pdf',
                        ],
                        'mimeTypesMessage' => 'Le fichier est invalide.',
                    ])
                ],
            ]);
        }

        $builder->add('submit', SubmitType::class, [
            'label' => $options['mode'] === 'CREATE' ? 'Créer le sujet' : 'Modifier le sujet'
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'mode' => 'CREATE'
        ]);
    }
}
