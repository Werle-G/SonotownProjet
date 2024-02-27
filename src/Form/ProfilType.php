<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Album;
use App\Entity\GenreMusical;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;


class ProfilType extends AbstractType
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
            ->add('nomArtiste', TextType::class, [
                'label' => 'Modifier votre nom d\'artiste',
                'attr' => ['class' => 'form-control']
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
