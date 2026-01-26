<?php

namespace App\Form;

use App\Entity\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class)
            ->add('lastName', TextType::class)
            ->add('companyName', TextType::class, ['required' => false])
            ->add('email', EmailType::class)
            ->add('address', TextType::class, ['required' => false])
            ->add('phoneNumber', TextType::class, ['required' => false])

            // New fields added to match the Client entity
            ->add('siret', TextType::class, [
                'required' => false,
                'attr' => ['maxlength' => 14],
            ])
            ->add('vatNumber', TextType::class, [
                'required' => false,
            ])
            ->add('city', TextType::class, [
                'required' => false,
            ])
            ->add('postCode', TextType::class, [
                'required' => false,
            ])
            ->add('country', TextType::class, [
                'required' => true,
                'empty_data' => 'France',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }
}
