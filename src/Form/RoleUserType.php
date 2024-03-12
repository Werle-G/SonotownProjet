<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Album;
use App\Entity\GenreMusical;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;


class RoleUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pseudo', TextType::class, [
                'label' => 'Pseudo',
                'attr' => ['class' => 'form-control']
            ])
            ->add('email', TextType::class, [
                'label' => 'Modifier votre adresse email',
                'attr' => ['class' => 'form-control']
            ])
            ->add('avatar', FileType::class, [
                'label' => false,
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '15254k', // Taille maximale du fichier
                        'mimeTypes' => [ // Types MIME autorisés
                            'image/jpeg',
                            'image/png',
                            'image/gif',
                        ],
                        'mimeTypesMessage' => 'Veuillez télécharger une image valide (JPEG, PNG ou GIF).',
                    ]),
                ]
            ])

            // ->add('plainPassword', RepeatedType::class, [
            //     'type' => PasswordType::class,
            //     'mapped' => false,
            //     'attr' => ['autocomplete' => 'new-password', 'class' => 'form-control'],
            //     'invalid_message' => 'Le mot de passe ne correspond pas',
            //     'required' => true,
            //     'first_options'  => ['label' => 'Entrez votre mot de passe'],
            //     'second_options' => ['label' => 'Répétez votre mot de passe'],
            //     'constraints' => [
            //         new Regex('/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{12,}$/'),
            //     ],
            // ])
            // ->add('newPassword', PasswordType::class, [
            //     'attr' => ['class' => 'form-control'],
            //     'label' => 'Nouveau mot de passe',
            //     'label_attr' => ['class' => 'form-label mt-4'],
            //     'constraints' => [new Assert\NotBlank()]
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
