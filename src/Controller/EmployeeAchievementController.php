<?php

namespace App\Controller;

use App\Entity\EmployeeAchievement;
use App\Form\EmployeeAchievementType;
use App\Repository\EmployeeAchievementRepository;
use App\Repository\EmployeeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/employee/achievement')]
final class EmployeeAchievementController extends AbstractController
{
    #[Route('/list/{employeeId}',name: 'app_employee_achievement_index', methods: ['GET'])]
    public function index(int $employeeId, EmployeeAchievementRepository $employeeAchievementRepository, EmployeeRepository $employeeRepository): Response
    {
        $employee = $employeeRepository->findOneBy(['id' => $employeeId]);

        return $this->render('employee_achievement/index.html.twig', [
            'employee_achievements' => $employeeAchievementRepository->findBy(['employee' => $employeeId]),
            'employee' => $employee,
        ]);
    }

    #[Route('/pdf-general', name: 'app_employee_achievement_all_pdf', methods: ['GET'])]
    public function generateGeneralPdf(EmployeeAchievementRepository $employeeAchievementRepository): Response
    {
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);


        $activities = $employeeAchievementRepository
            ->createQueryBuilder('ea')
            ->join('ea.employee', 'e')
            ->addSelect('e')
            ->orderBy('e.name', 'ASC')
            ->addOrderBy('ea.achievement_type', 'DESC')
            ->getQuery()
            ->getResult();

        $html = $this->renderView('employee_achievement/actividades-empleados-pdf.html.twig', [
            'activities' => $activities,
            'type'=> ''
        ]);


        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return new Response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="actividades-empleados.pdf"',
        ]);
    }

    #[Route('/pdf-achievements', name: 'app_employee_achievements_pdf', methods: ['GET'])]
    public function generateAchievementsPdf(EmployeeAchievementRepository $employeeAchievementRepository): Response
    {
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);


        $activities = $employeeAchievementRepository
            ->createQueryBuilder('ea')
            ->join('ea.employee', 'e')
            ->addSelect('e')
            ->where('ea.achievement_type = 1')
            ->orderBy('e.name', 'ASC')
            ->addOrderBy('ea.achievement_date', 'DESC')
            ->getQuery()
            ->getResult();

        $html = $this->renderView('employee_achievement/actividades-empleados-pdf.html.twig', [
            'activities' => $activities,
            'type'=> 'logros'
        ]);


        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return new Response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="logros-empleados.pdf"',
        ]);
    }

    #[Route('/pdf-warnings', name: 'app_employee_warnings_pdf', methods: ['GET'])]
    public function generateWarningsPdf(EmployeeAchievementRepository $employeeAchievementRepository): Response
    {
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);

        $activities = $employeeAchievementRepository
            ->createQueryBuilder('ea')
            ->join('ea.employee', 'e')
            ->addSelect('e')
            ->where('ea.achievement_type = 0')
            ->orderBy('e.name', 'ASC')
            ->addOrderBy('ea.achievement_date', 'DESC')
            ->getQuery()
            ->getResult();

        $html = $this->renderView('employee_achievement/actividades-empleados-pdf.html.twig', [
            'activities' => $activities,
            'type'=> 'llamadas de atencion'
        ]);


        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return new Response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="llamadas-atencion-empleados.pdf"',
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

            return $this->redirectToRoute('app_employee_achievement_index', ['employeeId' => $request->get('employee_id')], Response::HTTP_SEE_OTHER);
        }

        return $this->render('employee_achievement/new.html.twig', [
            'employee_achievement' => $employeeAchievement,
            'form' => $form,
            'employeeId' => $request->get('employee_id'),
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

        return $this->redirectToRoute('app_employee_achievement_index', ['employeeId' => $request->get('employeeId')], Response::HTTP_SEE_OTHER);
    }
}
