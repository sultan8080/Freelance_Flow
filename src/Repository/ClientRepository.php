<?php

namespace App\Repository;

use App\Entity\Client;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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
// this following function is to fetch clients and filter them by name or email
    public function findBySearch($user, string $query): array
    {
        $qb = $this->createQueryBuilder('c')
            ->where('c.user = :user')
            ->setParameter('user', $user);

        if ($query) {
            $qb->andWhere('c.firstName LIKE :q OR c.lastName LIKE :q OR c.email LIKE :q')
                ->setParameter('q', '%' . $query . '%');
        }

        return $qb->orderBy('c.lastName', 'ASC')->getQuery()->getResult();
    }
    //    /**
    //     * @return Client[] Returns an array of Client objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Client
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
