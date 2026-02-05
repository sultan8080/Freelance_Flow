<?php

namespace App\Form;

use App\Entity\Client;
use App\Entity\Invoice;
use App\Form\InvoiceItemType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class InvoiceType extends AbstractType
{
    public function __construct(private TranslatorInterface $translator)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $invoice = $event->getData();
            $form = $event->getForm();

            // The invoice is locked if: it exists, it has been saved (has an ID), and its status is not DRAFT
            $isLocked = $invoice && $invoice->getId() && $invoice->getStatus() !== 'DRAFT';
            
            //FIELDS (Locked if Sent/Paid) 

            $form->add('client', EntityType::class, [
                'class' => Client::class,
                'choice_label' => 'fullName',
                'placeholder' => 'Select a client', // Symfony translates this option automatically
                'attr' => ['class' => 'w-full rounded-2xl border-slate-200 focus:ring-primary'],
                'disabled' => $isLocked, 
            ]);

            $form->add('invoiceNumber', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'w-full rounded-2xl border-slate-200', 
                    'placeholder' => $this->translator->trans('Auto-generated') 
                ],
                'disabled' => $isLocked, 
            ]);

            $form->add('projectTitle', TextType::class, [
                'required' => false,
                'attr' => ['class' => 'w-full rounded-2xl border-slate-200'],
                'disabled' => $isLocked,
            ]);

            $form->add('currency', TextType::class, [
                'data' => 'EUR',
                'attr' => ['class' => 'w-full rounded-2xl border-slate-200'],
                'disabled' => $isLocked,
            ]);

            $form->add('invoiceItems', CollectionType::class, [
                'entry_type' => InvoiceItemType::class,
                'entry_options' => ['label' => false, 'disabled' => $isLocked],
                'allow_add' => !$isLocked,
                'allow_delete' => !$isLocked,
                'by_reference' => false,
                'label' => false,
            ]);

            // STATUS LOGIC ---

            // Default Choices (Draft, Sent, Paid)
            $statusChoices = [
                'Draft' => 'DRAFT',
                'Sent' => 'SENT',
                'Paid' => 'PAID'
            ];

            // If Locked (Sent/Paid), restrict choices to only Paid/Sent
            if ($isLocked) {
                $statusChoices = [
                    'Mark as Paid' => 'PAID',
                    'Revert to Sent (Unpaid)' => 'SENT'
                ];
            }

            $form->add('status', ChoiceType::class, [
                'choices' => $statusChoices, 
                'attr' => ['class' => 'w-full rounded-2xl border-slate-200 focus:ring-primary'],
                // Disable only if Draft (cannot change Draft when locked)
                // We want to allow changing status from Sent to Paid and vice versa even if locked
            ]);
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Invoice::class,
        ]);
    }
}