<?php

namespace App\Form;

use App\Entity\User;
use PharIo\Manifest\Email;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', type: EmailType::class, options: ['attr' => ['class' => 'mr-0 rounded rounded-m w-1/3', 'placeholder' => 'Email']])
            ->add('pseudo', TextType::class, options: ['attr' => ['class' => 'rounded rounded-m w-1/3', 'placeholder' => 'Pseudo']])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
                'attr' => ['class' => 'ml-2 rounded rounded-m'],
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'first_options' => ['attr' => ['placeholder' => 'Mot de passe', 'class' => 'rounded rounded-m w-1/3']],
                'second_options' => ['attr' => ['placeholder' => 'Confirmer le mot de passe', 'class' => 'rounded rounded-m w-1/3']],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    // new Length([
                    //     'min' => 6,
                    //     'minMessage' => 'Your password should be at least {{ limit }} characters',
                    //     // max length allowed by Symfony for security reasons
                    //     'max' => 4096,
                    // ]),
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
