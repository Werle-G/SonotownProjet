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
            // VichUploader
            // ->add('audioFile', FileType::class)
            ->add('audio', FileType::class, [
                'label' => 'Piste de l\'album',
                'attr' => ['class' => 'form-control'],
                'multiple' => true,
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new All([
                        'constraints' => [
                            new File([
                                'maxSize' => '15254k', 
                                'mimeTypes' => [ 
                                    'audio/mpeg',
                                    'audio/mp3',
                                    'audio/x-mpeg-3', 
                                ],
                                'mimeTypesMessage' => 'Veuillez télécharger un fichier audio valide (MP3).',
                            ]),
                        ]
                    ])
                ],
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
