<?php

namespace App\Controller;

use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(Connection $connection): Response
    {
        $qb = $connection->createQueryBuilder();
        $qb->select('*')
            ->from('yeti')
            ->orderBy('rating', 'DESC')
            ->setMaxResults(10);

        $bestYetis = $qb->executeQuery()->fetchAllAssociative();


        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'bestYetis' => $bestYetis,
        ]);


    }
}
