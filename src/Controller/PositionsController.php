<?php

namespace App\Controller;

use App\Entity\Positions;
use App\Form\PositionsType;
use App\Repository\PositionsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/positions')]
final class PositionsController extends AbstractController
{
    #[Route(name: 'app_positions_index', methods: ['GET'])]
    public function index(PositionsRepository $positionsRepository): Response
    {
        return $this->render('positions/index.html.twig', [
            'positions' => $positionsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_positions_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $position = new Positions();
        $form = $this->createForm(PositionsType::class, $position);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($position);
            $entityManager->flush();

            return $this->redirectToRoute('app_positions_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('positions/new.html.twig', [
            'position' => $position,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_positions_show', methods: ['GET'])]
    public function show(Positions $position): Response
    {
        return $this->render('positions/show.html.twig', [
            'position' => $position,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_positions_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Positions $position, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PositionsType::class, $position);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_positions_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('positions/edit.html.twig', [
            'position' => $position,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_positions_delete', methods: ['POST'])]
    public function delete(Request $request, Positions $position, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$position->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($position);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_positions_index', [], Response::HTTP_SEE_OTHER);
    }
}
