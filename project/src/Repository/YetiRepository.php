<?php

namespace App\Repository;

use App\Entity\Yeti;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Yeti>
 */
class YetiRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Yeti::class);
    }

    public function getRatingStatistics(): array
    {
        $qb = $this->createQueryBuilder('y');

        $qb->select('YEAR(y.createdAt) as year, MONTH(y.createdAt) as month, AVG(y.rating) as averageRating')
            ->groupBy('YEAR(y.createdAt), MONTH(y.createdAt)')
            ->orderBy('YEAR(y.createdAt), MONTH(y.createdAt)', 'ASC');

        return $qb->getQuery()->getArrayResult();
    }

    /**
     * @return Yeti[] Returns an array of Yeti objects
     */
    public function findByExampleField($value): array
    {
        return $this->createQueryBuilder('y')
            ->andWhere('y.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('y.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findOneBySomeField($value): ?Yeti
    {
        return $this->createQueryBuilder('y')
            ->andWhere('y.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
