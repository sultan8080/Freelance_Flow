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


    // --- ANALYTICS METHODS ---
    /**
     * Get all PAID invoices for a specific year (For the Chart & Revenue)
     */
    public function findPaidInvoicesByYear(User $user, int $year): array
    {
        $startDate = new \DateTimeImmutable("$year-01-01 00:00:00");
        $endDate = new \DateTimeImmutable("$year-12-31 23:59:59");

        return $this->createQueryBuilder('i')
            ->where('i.user = :user')
            ->andWhere('i.status = :status')
            ->andWhere('i.paidAt BETWEEN :startDate AND :endDate')
            ->setParameter('user', $user)
            ->setParameter('status', 'PAID')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->orderBy('i.paidAt', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Count total invoices for the year (Paid + Unpaid) (For KPI)
     */
    public function countInvoicesByYear(User $user, int $year): int
    {
        $startDate = new \DateTimeImmutable("$year-01-01 00:00:00");
        $endDate = new \DateTimeImmutable("$year-12-31 23:59:59");

        return $this->createQueryBuilder('i')
            ->select('COUNT(i)')
            ->where('i.user = :user')
            ->andWhere('i.createdAt BETWEEN :startDate AND :endDate')
            ->setParameter('user', $user)
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Get Top 5 Clients by Revenue (For the "Top Clients" List)
     */
    public function findTopClientsByRevenue(User $user, int $year, int $limit = 5): array
    {
        $startDate = new \DateTimeImmutable("$year-01-01 00:00:00");
        $endDate = new \DateTimeImmutable("$year-12-31 23:59:59");

        return $this->createQueryBuilder('i')
            ->select('c.name AS clientName, c.id AS clientId, SUM(i.totalHt) AS totalRevenue')
            ->join('i.client', 'c')
            ->where('i.user = :user')
            ->andWhere('i.status = :status')
            ->andWhere('i.paidAt BETWEEN :startDate AND :endDate')
            ->setParameter('user', $user)
            ->setParameter('status', 'PAID')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->groupBy('c.id')
            ->orderBy('totalRevenue', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Get Pending Invoices (Sent but not Paid) (For "Activity Feed")
     */
    public function findPendingInvoices(User $user): array
    {
        return $this->createQueryBuilder('i')
            ->where('i.user = :user')
            ->andWhere('i.status = :status')
            ->setParameter('user', $user)
            ->setParameter('status', 'SENT')
            ->orderBy('i.dueDate', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function getMonthlyTotalsByYear(User $user, int $year): array
    {
        $startDate = new \DateTimeImmutable("$year-01-01 00:00:00");
        $endDate = new \DateTimeImmutable("$year-12-31 23:59:59");

        return $this->createQueryBuilder('i')
            ->select('MONTH(i.paidAt) as month, SUM(i.totalHt) as total')
            ->where('i.user = :user')
            ->andWhere('i.status = :status')
            ->andWhere('i.paidAt BETWEEN :startDate AND :endDate')
            ->setParameter('user', $user)
            ->setParameter('status', 'PAID')
            ->groupBy('month')
            ->getQuery()
            ->getResult();
    }

    public function getYearlyTotalByYear(User $user, int $year): float
    {
        $startDate = new \DateTimeImmutable("$year-01-01 00:00:00");
        $endDate = new \DateTimeImmutable("$year-12-31 23:59:59");

        return (float) $this->createQueryBuilder('i')
            ->select('SUM(i.totalHt)')
            ->where('i.user = :user')
            ->andWhere('i.status = :status')
            ->andWhere('i.paidAt BETWEEN :startDate AND :endDate')
            ->setParameter('user', $user)
            ->setParameter('status', 'PAID')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Count how many invoices are actually PAID this year.
     */
    public function countPaidInvoicesByYear(User $user, int $year): int
    {
        $startDate = new \DateTimeImmutable("$year-01-01 00:00:00");
        $endDate = new \DateTimeImmutable("$year-12-31 23:59:59");

        return (int) $this->createQueryBuilder('i')
            ->select('COUNT(i.id)')
            ->where('i.user = :user')
            ->andWhere('i.status = :status')
            ->andWhere('i.paidAt BETWEEN :startDate AND :endDate')
            ->setParameter('user', $user)
            ->setParameter('status', 'PAID')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
