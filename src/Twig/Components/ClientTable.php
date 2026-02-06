<?php

namespace App\Twig\Components;

use App\Repository\ClientRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveListener;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('ClientTable')]
class ClientTable
{
    use DefaultActionTrait;

    #[LiveProp(writable: true)] // This allows the search input to change this value
    public string $query = '';

    // Pagination Props
    #[LiveProp(writable: true)]
    public int $page = 1;
    public int $itemsPerPage = 5;

    public function __construct(
        private ClientRepository $clientRepository,
        private Security $security
    ) {}

    #[LiveListener('updated:query')]
    public function resetPagination(): void
    {
        $this->page = 1;
    }

    #[LiveAction]
    public function setPage(#[LiveArg] int $page): void
    {
        if ($page < 1) return;
        $this->page = $page;
    }

    public function getClients(): Paginator
    {
        $user = $this->security->getUser();
        if (!$user) {
            // Return empty paginator if no user
            return new Paginator($this->clientRepository->createQueryBuilder('c')->where('1=0'));
        }   // Automatically every time 'query' changes
        return $this->clientRepository->findBySearch(
            $user,
            $this->query,
            $this->page,
            $this->itemsPerPage
        );
    }
}
