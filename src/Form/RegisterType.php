<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\EqualTo;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('full_name', TextType::class, [
                'label' => false,
                'constraints' => [
                    new NotBlank(['message' => 'Le nom complet est requis.']),
                    new Regex([
                        'pattern' => '/^[a-zA-Z\s]+$/',
                        'message' => 'Le nom ne doit contenir que des lettres et des espaces (pas de chiffres).',
                    ]),
                    new Length([
                        'min' => 2,
                        'minMessage' => 'Le nom doit contenir au moins {{ limit }} caractères.',
                        'max' => 100,
                        'maxMessage' => 'Le nom ne doit pas dépasser {{ limit }} caractères.',
                    ]),
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Full Name'
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => false,
                'constraints' => [
                    new NotBlank(['message' => 'L\'email est requis.']),
                    new Email(['message' => 'Veuillez saisir un email valide.']),
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Email'
                ],
            ])
            ->add('username', TextType::class, [
                'label' => false,
                'constraints' => [
                    new NotBlank(['message' => 'Le nom d\'utilisateur est requis.']),
                    new Length([
                        'min' => 3,
                        'minMessage' => 'Le nom d\'utilisateur doit contenir au moins {{ limit }} caractères.',
                        'max' => 50,
                        'maxMessage' => 'Le nom d\'utilisateur ne doit pas dépasser {{ limit }} caractères.',
                    ]),
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Username'
                ],
            ])
            ->add('password', PasswordType::class, [
                'label' => false,
                'constraints' => [
                    new NotBlank(['message' => 'Le mot de passe est requis.']),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Le mot de passe doit contenir au moins {{ limit }} caractères.',
                    ]),
                ],
                'attr' => [
                    'class' => 'form-control password',
                    'placeholder' => 'Password'
                ],
            ])
            ->add('password_confirm', PasswordType::class, [
                'label' => false,
                'constraints' => [
                    new NotBlank(['message' => 'La confirmation du mot de passe est requise.']),
                    new EqualTo([
                        'propertyPath' => 'password',
                        'message' => 'Les mots de passe ne correspondent pas.',
                    ]),
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Password again'
                ],
            ])
            ->add('receiveMail', CheckboxType::class, [
                'label' => 'Yes, I want to receive Duralux community emails',
                'required' => true,
                'label_attr' => ['class' => 'custom-control-label text-muted'],
                'attr' => ['class' => 'custom-control-input'],
            ])
            ->add('termsCondition', CheckboxType::class, [
                'label' => 'I agree to all the Terms & Conditions and Fees.',
                'required' => true,
                'label_attr' => ['class' => 'custom-control-label text-muted'],
                'attr' => ['class' => 'custom-control-input'],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Create Account',
                'attr' => ['class' => 'btn btn-lg btn-primary w-100'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Pas d'entité liée
        ]);
    }
}