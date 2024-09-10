<?php

namespace App\Controller;

use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class StatisticsController extends AbstractController
{
    #[Route('/statistics', name: 'yeti_statistics')]
    public function statistics(Connection $connection, string $period = 'day'): Response
    {
        $periods = [
            'year' => 'YEAR(created_at)',
            'month' => 'YEAR(created_at), MONTH(created_at)',
            'day' => 'DATE(created_at)'
        ];

        $periodSql = $periods[$period] ?? $periods['day'];

        $sql = "
        SELECT
            $periodSql AS period,
            AVG(score) AS average
        FROM rating
        WHERE score BETWEEN 0 AND 5
        GROUP BY $periodSql
        ORDER BY $periodSql
    ";

        $statistics = $connection->fetchAllAssociative($sql);

        // Debugging: Dump the statistics to see what’s being passed
        dump($statistics);

        return $this->render('statistics/index.html.twig', [
            'statistics' => $statistics,
            'period' => $period,
        ]);
    }


}
