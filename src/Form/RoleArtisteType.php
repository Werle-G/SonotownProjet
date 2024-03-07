<?php

namespace App\Form;

use App\Entity\Site;
use App\Entity\User;
use App\Entity\Album;
use App\Entity\Reseau;
use App\Entity\Concert;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class RoleArtisteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pseudo', TextType::class, [
                'label' => 'Changer de pseudo',
                'required' => true
            ])
            ->add('avatar', FileType::class, [
                'label' => 'avatar',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new Image([
                        'maxSize' => '5000k',
                    ])
                ],
            ])
            ->add('nomArtiste', TextType::class, [
                'label' => 'Entre un nom d\'artiste',
                'required' => true
            ])
            ->add('dateCreationGroupe', DateType::class, [
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control']
            ])
            ->add('biographie', TextareaType ::class, [
                'label' => 'Biographie',
                'required' => false,
                'attr' => ['class' => 'tinymce'],
            ])
            // ->add('sites', CollectionType::class, [
            //     'entry_type' => SiteType::class,
            //     'allow_add' => true, 
            //     'by_reference' => false, 
            // ])
            
            // ->add('sites', CollectionType::class, [
            //     'entry_type' => EmailType::class,
            //     'entry_options' => [
            //         'attr' => ['class' => 'email-box'],
            //     ],
            // ])
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
            'data_class' => User::class,
        ]);
    }
}
