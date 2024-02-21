<?php

namespace App\Form;

// use App\Entity\Concert;
use App\Entity\ImageConcert;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ImageConcertType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomImage', TextType::class, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('alt', TextType::class, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('concert', HiddenType::class, [
                'mapped' => false, 
            ])
            // ->add('valider', SubmitType::class, [
            //     'attr' => [
            //         'class' => 'btn btn-success'
            //     ]
            // ])
        ;
    }

    // ImageType est une classe qui hérite de fileType

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ImageConcert::class,
        ]);
    }
}
