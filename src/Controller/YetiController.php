<?php

namespace App\Controller;

use App\Entity\Yeti;
use App\Form\RatingType;
use App\Form\YetiType;
use App\Repository\YetiRepository;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/yeti')]
final class YetiController extends AbstractController
{


    #[Route(name: 'app_yeti_index', methods: ['GET'])]
    public function index(Connection $connection, YetiRepository $yetiRepository): Response
    {
        $sql = 'SELECT * FROM yeti ORDER BY RAND() LIMIT 1';
        $yeti = $connection->fetchAssociative($sql);


        return $this->render('yeti/show.html.twig', [
            'yeti' => $yeti,
        ]);
    }

    #[Route('/random', name: 'yeti_random')]
    public function showRandomYeti(Request $request, EntityManagerInterface $entityManager): Response
    {
        $yetis = $entityManager->getRepository(Yeti::class)->findAll();

        if (!$yetis) {
            throw $this->createNotFoundException('No Yetis found!');
        }

        $randomYeti = $yetis[array_rand($yetis)];

        $form = $this->createForm(RatingType::class, $randomYeti, [
            'action' => $this->generateUrl('yeti_rate', ['id' => $randomYeti->getId()])
        ]);

        return $this->render('yeti/show.html.twig', [
            'yeti' => $randomYeti,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/rate/{id}', name: 'yeti_rate', methods: ['POST'])]
    public function rateYeti(Request $request, Yeti $yeti, EntityManagerInterface $entityManager): Response
    {
        $rating = $request->request->get('rating');

        if ($rating) {
            $yeti->setRating((int) $rating);
            $entityManager->persist($yeti);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_yeti_show', ['id' => $yeti->getId()]);
    }


    #[Route('/{id}', name: 'app_yeti_show', methods: ['GET', 'POST'])]
    public function show(Yeti $yeti, Request $request, EntityManagerInterface $entityManager): Response
    {
        // Create the form and set the action to the route that handles form submission
        $form = $this->createForm(RatingType::class, $yeti, [
            'action' => $this->generateUrl('yeti_rate', ['id' => $yeti->getId()])
        ]);

        // Handle form submission
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            // Redirect after successful form submission
            return $this->redirectToRoute('app_yeti_show', ['id' => $yeti->getId()]);
        }

        return $this->render('yeti/show.html.twig', [
            'yeti' => $yeti,
            'form' => $form->createView(),
        ]);
    }


    #[Route('/statistics', name: 'yeti_statistics')]
    public function statistics(Connection $connection): Response
    {
        $sql = '
        SELECT
            YEAR(created_at) AS year,
            MONTH(created_at) AS month,
            AVG(score) AS average
        FROM rating
        GROUP BY year, month
        ORDER BY year, month
    ';
        $statistics = $connection->fetchAllAssociative($sql);

        return $this->render('yeti/statistics.html.twig', [
            'statistics' => $statistics,
        ]);
    }



    #[Route('/new', name: 'app_yeti_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $yeti = new Yeti();
        $form = $this->createForm(YetiType::class, $yeti);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($yeti);
            $entityManager->flush();

            return $this->redirectToRoute('app_yeti_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('yeti/new.html.twig', [
            'yeti' => $yeti,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_yeti_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Yeti $yeti, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(YetiType::class, $yeti);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_yeti_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('yeti/edit.html.twig', [
            'yeti' => $yeti,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_yeti_delete', methods: ['POST'])]
    public function delete(Request $request, Yeti $yeti, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$yeti->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($yeti);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_yeti_index', [], Response::HTTP_SEE_OTHER);
    }
}
