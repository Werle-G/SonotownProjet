<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Album;
use App\Form\PisteType;
use App\Entity\GenreMusical;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Image;
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
                'required' => false,
            ])
            ->add('photo', FileType::class, [
                'label' => 'imageAlbum',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new Image([
                        'maxSize' => '5000k',
                    ])
                ],
            ])
            ->add('dateSortieAlbum', DateType::class, [
                'widget' => 'single_text',
                'required' => false,
                'attr' => ['class' => 'form-control']
            ])
            ->add('nbPistes', IntegerType::class, [
                'required' => false,
                'attr' => ['class' => 'form-control']
            ])
            ->add('genreMusicals', EntityType::class, [
                'class' => GenreMusical::class,
                'choice_label' => 'nomGenreMusical',
                'multiple' => true,  
                'expanded' => true,  
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            // Collection attend l'élément qu'elle entrera dans le form. ce n'est pas obligatoire que ce soit un autre form/ peut être une entité
            ->add('pistes', CollectionType::class, [
                // entry_type : prend un formulaire piste
                'entry_type' => PisteType::class,
                // autoriser ajouter
                'allow_add' => true,
                // autoriser delete
                'allow_delete' => true,
                // permet d'ajouter un object en javascript/ autoriser ajout d'un nouvel élément dans l'entité album qui seront persisté grâce au cascade persist sur l'élément piste. activer data_prototype qui sera un attribut html qu'on pourra manipuler en js.
                'prototype' => true,
                // by_reference => false :car Album n'a pas de setPiste mais c'est Piste qui contient un setAlbum
                // Piste est propriétaire de la relation
                // Pour éviter un mapping false, on est obligé de rajouter by_reference false
                'by_reference' => false,
                // 
                'entry_options' => ['label' => false],
                // 'attr' => [
                //     'data-controller' => 'form-collection'
                // ]
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
