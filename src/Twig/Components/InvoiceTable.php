<?php

namespace App\Twig\Components;

use App\Repository\InvoiceRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('InvoiceTable')]
class InvoiceTable
{
    use DefaultActionTrait;

    #[LiveProp(writable: true)]
    public string $query = '';

    #[LiveProp(writable: true)]
    public string $status = '';

    public function __construct(
        private InvoiceRepository $invoiceRepository,
        private Security $security
    ) {}

    public function getInvoices(): array
    {
        $user = $this->security->getUser();

        if (!$user) {
            return [];
        }
        
        dump($this->status);
        return $this->invoiceRepository->searchInvoices($user, $this->query, $this->status);
    }
}
