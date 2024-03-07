<?php

namespace App\Form;

use App\Entity\Album;
use App\Entity\Piste;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class PisteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'label' => 'Titre de la chanson',
            ])
            ->add('duree', IntegerType::class, [
                'label' => 'Durée du morceau',
            ])
            ->add('audio', FileType::class, [
                // 'label' => 'pistes',
                'mapped' => false,
            ])
            // ->add('son', FileType::class, [
            //     'label' => 'audio',
            //     'mapped' => false,
            //     'required' => false,
            //     // 'label' => 'imageAlbum',
            //     'required' => false,
            // ])
            ->add('album', HiddenType::class, [
                'mapped' => false, 
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Piste::class,
        ]);
    }
}
