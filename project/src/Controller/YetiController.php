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
    #[Route('/all', name: 'app_yeti_index', methods: ['GET'])]
    public function index(YetiRepository $yetiRepository): Response
    {
        return $this->render('yeti/index.html.twig', [
            'yetis' => $yetiRepository->findAll(),
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
    #[Route('/', name: 'yeti_random')]
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

    #[Route('/stats', name: 'app_yeti_stats', methods: ['GET'])]
    public function stats(YetiRepository $yetiRepository): Response
    {
        // Fetch statistics data
        $stats = $yetiRepository->getRatingStatistics();

        // Render the statistics page with the data
        return $this->render('yeti/stats.html.twig', [
            'stats' => $stats,
        ]);
    }



}
