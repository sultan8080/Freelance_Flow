<?php

namespace App\Form;

use App\Entity\InvoiceItem;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class InvoiceItemType extends AbstractType
{
    public function __construct(private TranslatorInterface $translator) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description', TextType::class, [
                'label' => 'Description',
                'required' => true,
                'attr' => [
                    'placeholder' => $this->translator->trans('Item description / Service'),
                    'class' => 'w-full bg-transparent border-0 border-b border-slate-200 focus:ring-0 focus:border-primary px-2 py-2 text-sm transition-colors'
                ]
            ])
            ->add('quantity', NumberType::class, [
                'label' => 'Quantity',
                'required' => true,
                'html5' => true,
                'attr' => [
                    'min' => 1,
                    'step' => 1,
                    'placeholder' => '1',
                    'class' => 'w-full bg-transparent border-0 border-b border-slate-200 focus:ring-0 focus:border-primary px-2 py-2 text-sm text-right transition-colors'
                ]
            ])
            ->add('unitPrice', NumberType::class, [
                'label' => 'Price',
                'required' => true,
                'scale' => 2,
                'attr' => [
                    'placeholder' => '0.00',
                    'class' => 'w-full bg-transparent border-0 border-b border-slate-200 focus:ring-0 focus:border-primary px-2 py-2 text-sm text-right transition-colors'
                ]
            ])
            ->add('vatRate', NumberType::class, [
                'label' => 'VAT (if required) %',
                'required' => false,
                'scale' => 2,
                'attr' => [
                    'placeholder' => '00',
                    'class' => 'w-full bg-transparent border-0 border-b border-slate-200 focus:ring-0 focus:border-primary px-2 py-2 text-sm text-right transition-colors'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => InvoiceItem::class,
        ]);
    }
}
