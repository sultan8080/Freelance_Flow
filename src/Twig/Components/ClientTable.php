<?php

namespace App\Twig\Components;

use App\Repository\ClientRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
class ClientTable
{
    use DefaultActionTrait;

    #[LiveProp(writable: true)] // This allows the search input to change this value
    public string $query = '';

    public function __construct(
        private ClientRepository $clientRepository,
        private Security $security
    ) {}

    public function getClients(): array
    {
        // This is called automatically every time 'query' changes
        return $this->clientRepository->findBySearch(
            $this->security->getUser(),
            $this->query
        );
    }
}