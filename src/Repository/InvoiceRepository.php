<?php

namespace App\Repository;

use App\Entity\Invoice;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Invoice>
 */
class InvoiceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Invoice::class);
    }

    /**
     * Finds the last invoice number for a specific user and prefix (e.g., "INV-2026-")
     */
    public function findLastInvoiceNumberForUser(User $user, string $prefix): ?string
    {
        $result = $this->createQueryBuilder('i')
            ->select('i.invoiceNumber')
            ->where('i.user = :user')
            ->andWhere('i.invoiceNumber LIKE :prefix')
            ->setParameter('user', $user)
            ->setParameter('prefix', $prefix . '%') 
            ->orderBy('i.invoiceNumber', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        // If a result is found, return the invoice number string; otherwise return null
        return $result ? $result['invoiceNumber'] : null;
    }
}