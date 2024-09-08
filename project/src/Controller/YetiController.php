<?php

namespace App\Controller;

use App\Entity\Yeti;
use App\Form\YetiType;
use App\Repository\YetiRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/yeti')]
final class YetiController extends AbstractController
{


    #[Route(name: 'app_yeti_index', methods: ['GET'])]
    public function index(YetiRepository $yetiRepository): Response
    {
        return $this->render('yeti/index.html.twig', [
            'yetis' => $yetiRepository->findAll(),
        ]);
    }

    #[Route('/yeti/random', name: 'yeti_random')]
    public function showRandomYeti(EntityManagerInterface $entityManager): Response
    {
        $yetis = $entityManager->getRepository(Yeti::class)->findAll();

        if (!$yetis) {
            throw $this->createNotFoundException('No Yetis found!');
        }

        $randomYeti = $yetis[array_rand($yetis)];

        return $this->render('yeti/show.html.twig', [
            'yeti' => $randomYeti,
        ]);
    }
    #[Route('/yeti/rate/{id}', name: 'yeti_rate')]
    public function rateYeti(Yeti $yeti, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RatingType::class, $yeti);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($yeti);
            $entityManager->flush();

            return $this->redirectToRoute('yeti_random');
        }

        return $this->render('yeti/rate.html.twig', [
            'form' => $form->createView(),
            'yeti' => $yeti,
        ]);
    }

    #[Route('/yeti/statistics', name: 'yeti_statistics')]
    public function statistics(EntityManagerInterface $entityManager): Response
    {
        $query = $entityManager->createQuery(
            'SELECT AVG(y.rating) as avgRating, COUNT(y.id) as totalYetis
         FROM App\Entity\Yeti y'
        );
        $stats = $query->getSingleResult();

        return $this->render('yeti/statistics.html.twig', [
            'stats' => $stats,
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

    #[Route('/{id}', name: 'app_yeti_show', methods: ['GET'])]
    public function show(Yeti $yeti): Response
    {
        return $this->render('yeti/show.html.twig', [
            'yeti' => $yeti,
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
