<?php

namespace App\Form;

use App\Entity\Album;
use App\Entity\GenreMusical;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class GenreMusicalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomGenreMusical')
            // ->add('albums', EntityType::class, [
            ->add('album', HiddenType::class, [
                'mapped' => false, 
            ])
            // ->add('valider', SubmitType::class, [
            //     'attr' => [
            //         'class' => 'btn btn-success'
            //     ]
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => GenreMusical::class,
        ]);
    }
}
