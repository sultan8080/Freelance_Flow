<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
                'attr' => ['placeholder' => '123 456 789 00012']
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