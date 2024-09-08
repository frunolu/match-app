<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class YetiController extends AbstractController
{
    #[Route('/yeti', name: 'app_yeti')]
    public function index(): Response
    {
        return $this->render('yeti/index.html.twig', [
            'controller_name' => 'YetiController',
        ]);
    }
}
