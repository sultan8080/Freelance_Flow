<?php

namespace App\Twig\Components;

use App\Repository\InvoiceRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('InvoiceTable')]
class InvoiceTable
{
    public function __construct(
        private InvoiceRepository $invoiceRepository,
        private Security $security
    ) {
    }

    public function getInvoices(): array
    {
        // Fetch invoices for the currently logged-in user
        $user = $this->security->getUser();

        if (!$user) {
            return [];
        }

        return $this->invoiceRepository->findBy(
            ['user' => $user], 
            ['id' => 'DESC'] 
        );
    }
}