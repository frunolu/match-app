<?php

namespace App\Repository;

use App\Entity\Rating;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Rating>
 *
 * @method Rating|null find($id, $lockMode = null, $lockVersion = null)
 * @method Rating|null findOneBy(array $criteria, array $orderBy = null)
 * @method Rating[]    findAll()
 * @method Rating[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RatingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Rating::class);
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

    // Custom query to fetch ratings statistics grouped by date
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
