<?php

namespace App\Repository;

use App\Entity\Client;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Client>
 */
class ClientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Client::class);
    }

    public function findBySearch($user, string $query, int $page = 1, int $limit = 5): Paginator
    {
        $qb = $this->createQueryBuilder('c')
            ->where('c.user = :user')
            ->setParameter('user', $user);

        if ($query !== '') {
            $qb->andWhere('
                c.firstName LIKE :q OR 
                c.lastName LIKE :q OR 
                c.email LIKE :q OR 
                c.companyName LIKE :q
            ')
                ->setParameter('q', '%' . $query . '%');
        }
        // Sort by newest
        $qb->orderBy('c.createdAt', 'DESC');
        // return $qb->orderBy('c.lastName', 'ASC')
        //     ->getQuery()
        //     ->getResult();

        // Pagination Math
        $qb->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit);


        return new Paginator($qb, false);
    }

    /**
     * Count total active clients (Global)
     */
    public function countTotalClients(User $user): int
    {
        return $this->createQueryBuilder('c')
            ->select('count(c.id)')
            ->where('c.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Count clients added specifically in the SELECTED MONTH (Growth metric).
     */
    public function countNewClientsForMonth(User $user, \DateTimeInterface $date): int
    {
        // Clone date to get the first and last day of that specific month
        $start = \DateTimeImmutable::createFromInterface($date)->modify('first day of this month 00:00:00');
        $end = \DateTimeImmutable::createFromInterface($date)->modify('last day of this month 23:59:59');

        return $this->createQueryBuilder('c')
            ->select('count(c.id)')
            ->where('c.user = :user')
            ->andWhere('c.createdAt BETWEEN :start AND :end')
            ->setParameter('user', $user)
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
