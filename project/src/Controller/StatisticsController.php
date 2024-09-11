<?php

namespace App\Controller;

use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StatisticsController extends AbstractController
{
    #[Route('/statistics', name: 'yeti_statistics')]
    public function statistics(Connection $connection): Response
    {
        $sqlCountYetis = '
            SELECT rating, COUNT(*) AS count
            FROM yeti
            WHERE rating IS NOT NULL
            GROUP BY rating
            ORDER BY rating
        ';

        $statistics = $connection->fetchAllAssociative($sqlCountYetis);

        return $this->render('statistics/index.html.twig', [
            'statistics' => $statistics,
        ]);
    }
}
