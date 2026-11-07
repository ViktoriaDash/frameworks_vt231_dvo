<?php

namespace App\Controller;

use App\Entity\Penalty;
use App\Repository\PenaltyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/penalties', name: 'api_penalty_')]
class PenaltyController extends AbstractController
{
    /**
     * Отримати всі штрафи
     */
    #[Route('', name: 'index', methods: ['GET'])]
    public function index(PenaltyRepository $repository): JsonResponse
    {
        return $this->json($repository->findAll(), Response::HTTP_OK);
    }

    /**
     * Отримати штраф за ID
     */
    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(int $id, PenaltyRepository $repository): JsonResponse
    {
        $penalty = $repository->find($id);

        if (!$penalty) {
            return $this->json(['message' => 'Penalty not found'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($penalty, Response::HTTP_OK);
    }

    /**
     * Створити штраф
     */
    #[Route('', name: 'store', methods: ['POST'])]
    public function store(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $penalty = new Penalty();
        $penalty->setReaderName($data['reader_name'] ?? '');
        $penalty->setAmount($data['amount'] ?? 0);
        $penalty->setReason($data['reason'] ?? '');
        
        if (isset($data['is_paid'])) {
            $penalty->setIsPaid((bool) $data['is_paid']);
        }

        $entityManager->persist($penalty);
        $entityManager->flush();

        return $this->json($penalty, Response::HTTP_CREATED);
    }

    /**
     * Оновити штраф
     */
    #[Route('/{id}', name: 'update', methods: ['PUT', 'PATCH'])]
    public function update(int $id, Request $request, PenaltyRepository $repository, EntityManagerInterface $entityManager): JsonResponse
    {
        $penalty = $repository->find($id);

        if (!$penalty) {
            return $this->json(['message' => 'Penalty not found'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['reader_name'])) {
            $penalty->setReaderName($data['reader_name']);
        }
        if (isset($data['amount'])) {
            $penalty->setAmount($data['amount']);
        }
        if (isset($data['reason'])) {
            $penalty->setReason($data['reason']);
        }
        if (array_key_exists('is_paid', $data)) {
            $penalty->setIsPaid((bool) $data['is_paid']);
        }

        $entityManager->flush();

        return $this->json($penalty, Response::HTTP_OK);
    }

    /**
     * Видалити штраф
     */
    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function destroy(int $id, PenaltyRepository $repository, EntityManagerInterface $entityManager): JsonResponse
    {
        $penalty = $repository->find($id);

        if (!$penalty) {
            return $this->json(['message' => 'Penalty not found'], Response::HTTP_NOT_FOUND);
        }

        $entityManager->remove($penalty);
        $entityManager->flush();

        return $this->json(['message' => 'Penalty deleted successfully'], Response::HTTP_NO_CONTENT);
    }
}