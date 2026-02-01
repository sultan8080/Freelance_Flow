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
     * Finds the last invoice number for a given user with a specific prefix.
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

        return $result ? $result['invoiceNumber'] : null;
    }

    // --- ANALYTICS METHODS ---

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
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
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

    public function countInvoicesByYear(User $user, int $year): int
    {
        $startDate = new \DateTimeImmutable("$year-01-01 00:00:00");
        $endDate = new \DateTimeImmutable("$year-12-31 23:59:59");

        return (int) $this->createQueryBuilder('i')
            ->select('COUNT(i.id)')
            ->where('i.user = :user')
            ->andWhere('i.createdAt BETWEEN :startDate AND :endDate')
            ->setParameter('user', $user)
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Get Top 5 Clients by Revenue for a Given Year
     */
    public function findTopClientsByRevenue(User $user, int $year, int $limit = 5): array
    {
        $startDate = new \DateTimeImmutable("$year-01-01 00:00:00");
        $endDate = new \DateTimeImmutable("$year-12-31 23:59:59");

        return $this->createQueryBuilder('i')
            ->select('c.firstName, c.lastName, c.companyName, c.id AS clientId, SUM(i.totalHt) AS totalRevenue')
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


    // HOME DASHBOARD METHODS //

    /**
     * Get revenue strictly for the CURRENT month 
     */
    public function getCurrentMonthRevenue(User $user): float
    {
        $start = new \DateTimeImmutable('first day of this month 00:00:00');
        $end = new \DateTimeImmutable('last day of this month 23:59:59');

        $result = $this->createQueryBuilder('i')
            ->select('SUM(i.totalHt)')
            ->where('i.user = :user')
            ->andWhere('i.status = :status')
            ->andWhere('i.paidAt BETWEEN :start AND :end')
            ->setParameter('user', $user)
            ->setParameter('status', 'PAID')
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->getQuery()
            ->getSingleScalarResult();

        return (float) $result;
    }

    /**
     * 5 most recent invoices  
    */
    public function findRecentInvoices(User $user, int $limit = 5): array
    {
        return $this->createQueryBuilder('i')
            ->where('i.user = :user')
            ->setParameter('user', $user)
            ->orderBy('i.createdAt', 'DESC') // Newest first
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Count invoices by status 
     */
    public function countByStatus(User $user, string $status): int
    {
        return (int) $this->createQueryBuilder('i')
            ->select('COUNT(i.id)')
            ->where('i.user = :user')
            ->andWhere('i.status = :status')
            ->setParameter('user', $user)
            ->setParameter('status', $status)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
