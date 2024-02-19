<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Album;
use App\Form\PisteType;
use App\Entity\GenreMusical;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class AlbumType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomAlbum', TextType::class, [
                'label' => 'nomAlbum',
            ])
            ->add('description')
            ->add('imageAlbum')
            ->add('dateSortieAlbum', DateType::class, [
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control']
            ])
            ->add('nbPistes')
            ->add('ban')
            ->add('genreMusicals', EntityType::class, [
                'class' => GenreMusical::class,
                'choice_label' => 'nomGenreMusical',
                'multiple' => true, // Permet la sélection de plusieurs genres musicaux
                'expanded' => true, // Affiche les genres musicaux sous forme de cases à cocher
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
