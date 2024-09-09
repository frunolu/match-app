<?php

namespace App\Controller;

use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class StatisticsController extends AbstractController
{
    #[Route('/statistics', name: 'yeti_statistics')]
    public function statistics(Connection $connection): Response
    {
        $sql = '
        SELECT
            YEAR(created_at) AS year,
            MONTH(created_at) AS month,
            DAY(created_at) AS day,
            AVG(score) AS average
        FROM rating
        GROUP BY year, month, day
        ORDER BY year, month, day
    ';
        $statistics = $connection->fetchAllAssociative($sql);

        return $this->render('yeti/statistics.html.twig', [
            'statistics' => $statistics,
        ]);
    }
}
