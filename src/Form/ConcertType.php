<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Concert;
use App\Form\ImageConcertType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class ConcertType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lieu', TextType::class, [
                'label' => 'Lieu',
            ])
            ->add('dateConcert', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date concert',
                'attr' => ['class' => 'form-control']
            ])
            ->add('descriptionConcert', TextType::class, [
                'label' => 'Description concert',
            ])
            ->add('imageConcerts', CollectionType:: class, [
                'entry_type' => ImageConcertType::class, 
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
            'data_class' => Concert::class,
        ]);
    }
}
