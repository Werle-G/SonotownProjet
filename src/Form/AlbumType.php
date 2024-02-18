<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Album;
use App\Entity\GenreMusical;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AlbumType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomAlbum')
            ->add('description')
            ->add('imageAlbum')
            ->add('dateSortieAlbum')
            ->add('nbPistes')
            ->add('ban')
            ->add('genreMusicals', EntityType::class, [
                'class' => GenreMusical::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
//             ->add('aimers', EntityType::class, [
//                 'class' => User::class,
// 'choice_label' => 'id',
// 'multiple' => true,
//             ])
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
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
