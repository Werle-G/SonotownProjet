<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Album;
use App\Form\PisteType;
use App\Entity\GenreMusical;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class AlbumType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomAlbum', TextType::class, [
                'label' => 'Nom de l\'album',
            ])
            ->add('description', TextType::class, [
                'label' => 'Description',
            ])
            ->add('imageAlbum', FileType::class, [
                'label' => 'Pochette de l\'album',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'application/pdf',
                            'application/x-pdf',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid PDF document',
                    ])
                ],
            ])
            ->add('dateSortieAlbum', DateType::class, [
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control']
            ])
            ->add('nbPistes', IntegerType::class, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('genreMusicals', CollectionType::class, [
                'entry_type' => EntityType::class,
                'entry_options' => [
                    'class' => GenreMusical::class,
                    'choice_label' => 'nomGenreMusical',
                ],
                'allow_add' => true, // Permet d'ajouter de nouveaux genres musicaux à la collection
                'allow_delete' => true, // Permet de supprimer des genres musicaux de la collection
                'by_reference' => false, // Obligatoire lorsque vous utilisez un CollectionType avec une relation ManyToMany
            ])
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'pseudo',
            ])
            ->add('pistes', CollectionType::class, [
                'entry_type' => PisteType::class,
                'allow_add' => true, 
                'by_reference' => false, 
            ])
            ->add('valider', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-success'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Album::class,
        ]);
    }
}
