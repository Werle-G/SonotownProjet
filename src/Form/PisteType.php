<?php

namespace App\Form;

use App\Entity\Album;
use App\Entity\Piste;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\All;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
            // ->add('audio', FileType::class, [
            //     'label' => '',
            //     'attr' => ['class' => 'form-control'],
            //     'multiple' => false,
            //     // 'mapped' => false,
            //     'required' => false,
                
                 
            //             'constraints' => [
            //                 new File ([
            //                     'maxSize' => '100000k',
            //                     // 'mimeTypes' => [
            //                     //     'audio/mp3'
            //                     // ],
            //                     // 'mimeTypesMessage' => 'Please upload une image valide',
            //                 ]),
            //             ]                    
                    
                
            // ])
            ->add('audio', FileType::class,[
                'label' => false,
                'multiple' => false,
                'mapped' => false,
                'required' => false,
                new All ([
                    'constraints' => [
                        new File ([
                            'maxSize' => '1000000k',
                            'mimeTypes' => [
                                'audio/mp3',
        
                            ],
                            'mimeTypesMessage' => 'Please upload une image valide',
                        ]),
                    ]                    
                ])
               
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
