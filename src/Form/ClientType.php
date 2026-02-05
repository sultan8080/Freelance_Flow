<?php

namespace App\Form;

use App\Entity\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class ClientType extends AbstractType
{
    public function __construct(private TranslatorInterface $translator) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'label' => 'First Name', // Symfony translates labels automatically if they match keys
                'attr' => ['placeholder' => $this->translator->trans('John')], // Attributes need manual translation
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Last Name',
                'attr' => ['placeholder' => $this->translator->trans('Doe')],
            ])
            ->add('companyName', TextType::class, [
                'required' => false,
                'label' => 'Company Name',
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email Address',
            ])
            ->add('address', TextType::class, [
                'required' => false,
                'label' => 'Address',
            ])
            ->add('phoneNumber', TextType::class, [
                'required' => false,
                'label' => 'Phone Number',
            ])

            // New fields added to match the Client entity
            ->add('siret', TextType::class, [
                'required' => false,
                'label' => 'SIRET',
                'attr' => ['maxlength' => 14],
            ])
            ->add('vatNumber', TextType::class, [
                'required' => false,
                'label' => 'VAT Number',
            ])
            ->add('city', TextType::class, [
                'required' => false,
                'label' => 'City',
            ])
            ->add('postCode', TextType::class, [
                'required' => false,
                'label' => 'Post Code',
            ])
            ->add('country', TextType::class, [
                'required' => true,
                'label' => 'Country',
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
