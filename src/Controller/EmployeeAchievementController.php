<?php

namespace App\Controller;

use App\Entity\EmployeeAchievement;
use App\Form\EmployeeAchievementType;
use App\Repository\EmployeeAchievementRepository;
use App\Repository\EmployeeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/employee/achievement')]
final class EmployeeAchievementController extends AbstractController
{
    #[Route(name: 'app_employee_achievement_index', methods: ['GET'])]
    public function index(EmployeeAchievementRepository $employeeAchievementRepository): Response
    {
        return $this->render('employee_achievement/index.html.twig', [
            'employee_achievements' => $employeeAchievementRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_employee_achievement_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        EmployeeRepository  $employeeRepository,
    ): Response
    {
        $employeeAchievement = new EmployeeAchievement();
        $employee = $employeeRepository->findOneBy(['id' => $request->get('employee_id')]);
        $form = $this->createForm(EmployeeAchievementType::class, $employeeAchievement, [
            'employee' => $employee,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $employeeAchievement->setEmployee($employee);
            $entityManager->persist($employeeAchievement);
            $entityManager->flush();

            return $this->redirectToRoute('app_employee_achievement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('employee_achievement/new.html.twig', [
            'employee_achievement' => $employeeAchievement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_employee_achievement_show', methods: ['GET'])]
    public function show(EmployeeAchievement $employeeAchievement): Response
    {
        return $this->render('employee_achievement/show.html.twig', [
            'employee_achievement' => $employeeAchievement,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_employee_achievement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, EmployeeAchievement $employeeAchievement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EmployeeAchievementType::class, $employeeAchievement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_employee_achievement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('employee_achievement/edit.html.twig', [
            'employee_achievement' => $employeeAchievement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_employee_achievement_delete', methods: ['POST'])]
    public function delete(Request $request, EmployeeAchievement $employeeAchievement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$employeeAchievement->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($employeeAchievement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_employee_achievement_index', [], Response::HTTP_SEE_OTHER);
    }
}
