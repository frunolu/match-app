<?php

namespace App\Repository;

use App\Entity\Yeti;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Yeti>
 *
 * @method Yeti|null find($id, $lockMode = null, $lockVersion = null)
 * @method Yeti|null findOneBy(array $criteria, array $orderBy = null)
 * @method Yeti[]    findAll()
 * @method Yeti[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StatisticsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Yeti::class);
    }

    // Custom query to find average score for a Yeti by its ID
    public function findAverageScoreForYeti(int $yetiId): ?float
    {
        return $this->createQueryBuilder('r')
            ->select('AVG(r.score) as avgScore')
            ->andWhere('r.yeti = :yetiId')
            ->setParameter('yetiId', $yetiId)
            ->getQuery()
            ->getSingleScalarResult();
    }

    // Custom query to fetch Yetis statistics grouped by date
    public function findRatingStatisticsGroupedByDate(): array
    {
        return $this->createQueryBuilder('r')
            ->select('YEAR(r.createdAt) as year, MONTH(r.createdAt) as month, DAY(r.createdAt) as day, AVG(r.score) as average')
            ->groupBy('year, month, day')
            ->orderBy('year', 'ASC')
            ->addOrderBy('month', 'ASC')
            ->addOrderBy('day', 'ASC')
            ->getQuery()
            ->getResult();
    }

    // Custom query to find all ratings for a specific Yeti
    public function findRatingsByYeti(int $yetiId): array
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.yeti = :yetiId')
            ->setParameter('yetiId', $yetiId)
            ->orderBy('r.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
