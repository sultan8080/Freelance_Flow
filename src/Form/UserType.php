<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // We remove email, roles, password, createdAt, and updatedAt
            ->add('firstName', TextType::class, [
                'label' => 'First Name',
                'attr' => ['placeholder' => 'John']
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Last Name',
                'attr' => ['placeholder' => 'Doe']
            ])
            ->add('companyName', TextType::class, [
                'label' => 'Company Name',
                'attr' => ['placeholder' => 'My Freelance Studio']
            ])
            ->add('address', TextType::class, [
                'label' => 'Professional Address',
                'attr' => ['placeholder' => '123 Street Name, City']
            ])
            ->add('siretNumber', TextType::class, [
                'label' => 'SIRET Number',
                'required' => true, // Enforces the HTML5 "required" attribute
                'attr' => ['placeholder' => '12345678900012'],
                'constraints' => [
                    new NotBlank(message: 'A SIRET number is mandatory for your invoices.'),
                    new Regex(
                        pattern: '/^\d{14}$/',
                        message: 'Your SIRET must be exactly 14 digits.'
                    )
                ]
            ])
            ->add('vatNumber', TextType::class, [
                'label' => 'VAT Number (Optional)',
                'required' => false,
                'attr' => ['placeholder' => 'FR 12 345678901']
            ])
            ->add('phoneNumber', TelType::class, [
                'label' => 'Phone Number',
                'required' => false,
                'attr' => ['placeholder' => '+33 6 00 00 00 00']
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
