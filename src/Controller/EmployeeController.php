<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Form\EmployeeType;
use App\Repository\EmployeeAchievementRepository;
use App\Repository\EmployeeRepository;
use App\Service\CloudinaryService;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/employee')]
final class EmployeeController extends AbstractController
{

    #[Route(name: 'app_employee_index', methods: ['GET'])]
    public function index(EmployeeRepository $employeeRepository): Response
    {
        return $this->render('employee/index.html.twig', [
            'employees' => $employeeRepository->findAll(),
        ]);
    }

    #[Route('/pdf', name: 'app_employee_pdf_index', methods: ['GET'])]
    public function generatePdf(EmployeeRepository $employeeRepository): Response
    {
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);


        $empleados = $employeeRepository->findAll();
        $total = $employeeRepository
            ->createQueryBuilder('e')
            ->select('sum(e.salary)');

        $html = $this->renderView('employee/empleados-pdf.html.twig', [
            'empleados' => $empleados,
            'total' => (float) $total->getQuery()->getSingleScalarResult(),
        ]);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return new Response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="empleados.pdf"',
        ]);
    }

    #[Route('/pdf-detail/{id}', name: 'app_employee_detail_pdf', methods: ['GET'])]
    public function generateDetailPdf(int $id, EmployeeRepository $employeeRepository): Response
    {
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);

        $empleado = $employeeRepository->findOneBy(['id' => $id]);

        $html = $this->renderView('employee/empleados-detail-pdf.html.twig', [
            'empleado' => $empleado,
            'activities' => $empleado->getEmployeeAchievements(),
        ]);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return new Response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="empleado-detalle.pdf"',
        ]);
    }


    #[Route('/new', name: 'app_employee_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $employee = new Employee();
        $form = $this->createForm(EmployeeType::class, $employee, [
            'is_create' => true,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();
            $cloudinaryService = new CloudinaryService();
            $uploadResult = $cloudinaryService->upload($imageFile);
            $employee->setPictureUrl($uploadResult['secure_url']);
            $employee->setPublicImageId($uploadResult['public_id']);
            $entityManager->persist($employee);
            $entityManager->flush();

            return $this->redirectToRoute('app_employee_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('employee/new.html.twig', [
            'employee' => $employee,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_employee_show', methods: ['GET'])]
    public function show(Employee $employee): Response
    {
        return $this->render('employee/show.html.twig', [
            'employee' => $employee,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_employee_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Employee $employee, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EmployeeType::class, $employee);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $cloudinaryService = new CloudinaryService();
                $cloudinaryService->destroy($employee->getPublicImageId());
                $uploadResult = $cloudinaryService->upload($imageFile);
                $employee->setPictureUrl($uploadResult['secure_url']);
                $employee->setPublicImageId($uploadResult['public_id']);
            }
            $entityManager->flush();

            return $this->redirectToRoute('app_employee_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('employee/edit.html.twig', [
            'employee' => $employee,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_employee_delete', methods: ['POST'])]
    public function delete(Request $request, Employee $employee, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $employee->getId(), $request->getPayload()->getString('_token'))) {
            $cloudinaryService = new CloudinaryService();
            $cloudinaryService->destroy($employee->getPublicImageId());
            $entityManager->remove($employee);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_employee_index', [], Response::HTTP_SEE_OTHER);
    }
}
