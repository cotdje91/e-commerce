<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class,
            [
                'attr'=>[
                    'class' => 'form-control'
                ],
                'label' => 'E-mail',
            ])

            ->add('lastname',TextType::class,
            [
                'attr'=>[
                    'class' => 'form-control'
                ],
                'label' => 'Nom',
            ])

            ->add('firstname', TextType::class,
            [             
                'attr'=>[
                    'class' => 'form-control'
                ],
                'label' => 'Prénom',

            ])

            ->add('address', TextType::class,
            [             
                'attr'=>[
                    'class' => 'form-control'
                ],
                'label' => 'Adresse',

            ])

            ->add('zipcode', TextType::class,
            [             
                'attr'=>[
                    'class' => 'form-control'
                ],
                'label' => 'Code postale',

            ])

            ->add('city',TextType::class,
            [
                'attr'=>[
                    'class' => 'form-control'
                ],
                'label' => 'Ville',

            ])
            
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => "Merci de valider les codition d'utilisation.",
                    ]),
                ],
                'label' => "accepter les codition d'utilisations"
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => 
                ['autocomplete' => 'new-password',
                'class' => 'form-control'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'entrez un mot de passe',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit contenir au minimum {{ limit }} characteres',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
                'label' => 'mot de passe',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}