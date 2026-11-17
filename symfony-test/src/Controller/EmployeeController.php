<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Repository\EmployeeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/employees', name: 'api_employee_')]
class EmployeeController extends AbstractController
{
    /**
     * Отримати список працівників з фільтрацією та пагінацією
     * 
     * @param Request $request
     * @param EmployeeRepository $repository
     * @return JsonResponse
     */
    #[Route('', name: 'index', methods: ['GET'])]
    public function index(Request $request, EmployeeRepository $repository): JsonResponse
    {
        $allowedParams = ['id', 'name', 'position', 'phone'];

        try {
            $employees = $repository->filterAndPaginate($request, $allowedParams);
        } catch (\InvalidArgumentException $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        if (empty($employees['data'])) {
            return $this->json(['message' => 'Працівників не знайдено'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($employees, Response::HTTP_OK);
    }

    /**
     * Отримати працівника за ID
     * 
     * @param int $id
     * @param EmployeeRepository $repository
     * @return JsonResponse
     */
    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(int $id, EmployeeRepository $repository): JsonResponse
    {
        $employee = $repository->find($id);

        if (!$employee) {
            return $this->json(['message' => 'Працівника не знайдено'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($employee, Response::HTTP_OK);
    }

    /**
     * Створити нового працівника
     * 
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return JsonResponse
     */
    #[Route('', name: 'store', methods: ['POST'])]
    public function store(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $employee = new Employee();
        $employee->setName($data['name'] ?? '');
        $employee->setPosition($data['position'] ?? '');
        
        if (isset($data['phone'])) {
            $employee->setPhone($data['phone']);
        }

        $entityManager->persist($employee);
        $entityManager->flush();

        return $this->json($employee, Response::HTTP_CREATED);
    }

    /**
     * Оновити дані працівника
     * 
     * @param int $id
     * @param Request $request
     * @param EmployeeRepository $repository
     * @param EntityManagerInterface $entityManager
     * @return JsonResponse
     */
    #[Route('/{id}', name: 'update', methods: ['PUT', 'PATCH'])]
    public function update(int $id, Request $request, EmployeeRepository $repository, EntityManagerInterface $entityManager): JsonResponse
    {
        $employee = $repository->find($id);

        if (!$employee) {
            return $this->json(['message' => 'Працівника не знайдено'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['name'])) {
            $employee->setName($data['name']);
        }
        if (isset($data['position'])) {
            $employee->setPosition($data['position']);
        }
        if (array_key_exists('phone', $data)) {
            $employee->setPhone($data['phone']);
        }

        $entityManager->flush();

        return $this->json($employee, Response::HTTP_OK);
    }

    /**
     * Видалити працівника
     * 
     * @param int $id
     * @param EmployeeRepository $repository
     * @param EntityManagerInterface $entityManager
     * @return JsonResponse
     */
    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function destroy(int $id, EmployeeRepository $repository, EntityManagerInterface $entityManager): JsonResponse
    {
        $employee = $repository->find($id);

        if (!$employee) {
            return $this->json(['message' => 'Працівника не знайдено'], Response::HTTP_NOT_FOUND);
        }

        $entityManager->remove($employee);
        $entityManager->flush();

        return $this->json(['message' => 'Працівника видалено'], Response::HTTP_NO_CONTENT);
    }
}