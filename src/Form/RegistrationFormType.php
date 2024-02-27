<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('role', ChoiceType::class, [
                'choices' => [
                    'Compte fan' => 'ROLE_USER',
                    'Compte artiste' => 'ROLE_ARTISTE',
                ],
                'mapped' => false, 
                'expanded' => true, 
                'multiple' => false, 
            ])
            ->add('pseudo', TextType::class, [
                'label' => 'Entrez un nom d\'utilisateur',
                'attr' => ['class' => 'form-control']
            ])
            ->add('nomArtiste', TextType::class, [
                'label' => 'Entrez un nom d\'artiste',
                'attr' => ['class' => 'form-control']
            ])
            ->add('email', TextType::class, [
                'label' => 'Entrez votre email',
                'attr' => ['class' => 'form-control']
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'invalid_message' => 'Le mot de passe ne correspond pas',
                'options' => ['attr' => ['class' => 'password-field'],
                'attr' => ['class' => 'form-control']],
                'required' => true,
                'first_options'  => ['label' => 'Entrez votre mot de passe'],
                'second_options' => ['label' => 'Répétez votre mot de passe'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Entrez votre mot de passe',
                    ]),
                    new Regex('/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{4,}$/',
                    ),
                ],
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez acceptez les conditions d\'utilisations',
                    ]),
                ],
            ])
            ->add('ageLegal', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                    'message' => 'En cochant cette case, vous confirmer avoir 15 ans',
                    ]),
                ],
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
