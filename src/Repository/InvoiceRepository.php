<?php

namespace App\Repository;

use App\Entity\Invoice;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
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


    /**
     * Get pending invoiecs by eager-loaded
     */
    public function findPendingInvoices(User $user): array
    {
        return $this->createQueryBuilder('i')
            ->addSelect('c')
            ->leftJoin('i.client', 'c')
            ->where('i.user = :user')
            ->andWhere('i.status = :status')
            ->setParameter('user', $user)
            ->setParameter('status', 'SENT')
            ->orderBy('i.dueDate', 'ASC')
            ->getQuery()
            ->getResult();
    }


    // --- HOME DASHBOARD METHODS ---

    /**
     * Get revenue strictly for the SELECTED month passed in $date
     */
    public function getRevenueForMonth(User $user, \DateTimeInterface $date): float
    {
        $start = \DateTimeImmutable::createFromInterface($date)->modify('first day of this month 00:00:00');
        $end = \DateTimeImmutable::createFromInterface($date)->modify('last day of this month 23:59:59');

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
     * 5 most recent invoices by eager-loaded
     */
    public function findRecentInvoices(User $user, int $limit = 5): array
    {
        return $this->createQueryBuilder('i')
            ->addSelect('c')
            ->leftJoin('i.client', 'c')
            ->where('i.user = :user')
            ->setParameter('user', $user)
            ->orderBy('i.createdAt', 'DESC')
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

    /**
     * Fetches all invoices for a user with Client and Items eager-loaded.
     */
    public function findAllForUserWithRelations(User $user): array
    {
        return $this->createQueryBuilder('i')
            ->addSelect('c', 'it')
            ->leftJoin('i.client', 'c')
            ->leftJoin('i.invoiceItems', 'it')
            ->where('i.user = :user')
            ->setParameter('user', $user)
            ->orderBy('i.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
    public function searchInvoices(User $user, string $query, string $status = '', int $page = 1, int $limit = 10): Paginator
    {
        $qb = $this->createQueryBuilder('i')
            ->addSelect('c')
            ->leftJoin('i.client', 'c')
            ->where('i.user = :user')
            ->setParameter('user', $user)
            ->orderBy('i.createdAt', 'DESC');

        // query by text inside search field
        if ($query) {
            $qb->andWhere('
                i.invoiceNumber LIKE :query OR 
                c.companyName LIKE :query OR 
                i.projectTitle LIKE :query
            ')
                ->setParameter('query', '%' . $query . '%');
        }

        // Handle status filtering
        if ($status) {
            if ($status === 'OVERDUE') {

                $qb->andWhere('i.status = :sentStatus')
                    ->andWhere('i.dueDate < :today')
                    ->setParameter('sentStatus', 'SENT')
                    ->setParameter('today', new \DateTimeImmutable('today'));
            } else {

                $qb->andWhere('i.status = :status')
                    ->setParameter('status', $status);
            }
        }
        // Pagination Math
        $qb->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit);

        // Create Paginator
        return new Paginator($qb, false);
    }
}
