<?php

namespace App\Form;

use App\Entity\Concert;
use App\Entity\ImageConcert;
use App\Form\ImageConcertType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ImageConcertType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomImage')
            ->add('alt')
            // ->add('concert', EntityType::class, [
            //     'class' => Concert::class,
            //     'choice_label' => 'id',
            // ])
            ->add('concert', HiddenType::class, [
                'mapped' => false, 
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
            'data_class' => ImageConcert::class,
        ]);
    }
}
