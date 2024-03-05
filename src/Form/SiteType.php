<?php

namespace App\Form;

use App\Entity\Site;
use App\Entity\User;
use App\Entity\Reseau;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SiteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('adresse')
            // ->add('adresse', TextType::class, [
            //     'label' => 'Entrez un site',
            //     'attr' => [
            //         'class' => 'form-control'
            //     ]
            // ])
            ->add('reseau', EntityType::class, [
                'class' => Reseau::class,
                'choice_label' => 'nomSite',
                'multiple' => true,  
                'expanded' => true,  
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('user', HiddenType::class, [
                'mapped' => false,
            ])
            // ->add('Valider', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Site::class,
        ]);
    }
}
