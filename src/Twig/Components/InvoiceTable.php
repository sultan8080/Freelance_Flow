<?php

namespace App\Twig\Components;

use App\Repository\InvoiceRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveListener;
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

    // pagination
    #[LiveProp(writable: true)]
    public int $page = 1;
    public int $itemsPerPage = 10;

    public function __construct(
        private InvoiceRepository $invoiceRepository,
        private Security $security
    ) {}

    #[LiveListener('updated:query')]
    #[LiveListener('updated:status')]
    public function resetPagination(): void
    {
        $this->page = 1;
    }

    public function getInvoices(): Paginator
    {
        $user = $this->security->getUser();

        if (!$user) {
            return new Paginator(
                $this->invoiceRepository->createQueryBuilder('i')
                    ->where('1 = 0')
            );
        }

        return $this->invoiceRepository->searchInvoices(
            $user,
            $this->query,
            $this->status,
            $this->page,
            $this->itemsPerPage
        );
    }
}
