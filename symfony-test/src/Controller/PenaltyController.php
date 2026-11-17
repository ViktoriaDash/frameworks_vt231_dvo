<?php

namespace App\Controller;

use App\Entity\Penalty;
use App\Repository\PenaltyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PenaltyController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * @var PenaltyRepository
     */
    private PenaltyRepository $penaltyRepository;

    /**
     * Конструктор контролера
     * 
     * @param EntityManagerInterface $entityManager
     * @param PenaltyRepository $penaltyRepository
     */
    public function __construct(EntityManagerInterface $entityManager, PenaltyRepository $penaltyRepository)
    {
        $this->entityManager = $entityManager;
        $this->penaltyRepository = $penaltyRepository;
    }

    /**
     * Отримати список штрафів з фільтрацією та пагінацією
     * 
     * @param Request $request
     * @return JsonResponse
     */
    #[Route('/api/penalties', name: 'app_get_penalties', methods: ['GET'])]
    public function getPenalties(Request $request): JsonResponse
    {
        $allowedParams = ['id', 'reader_name', 'amount', 'reason', 'is_paid'];

        try {
            $penalties = $this->penaltyRepository->filterAndPaginate($request, $allowedParams);
        } catch (\InvalidArgumentException $e) {
            return $this->json(['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        if (empty($penalties['data']) && empty($penalties)) {
            return $this->json(['message' => 'No penalties found matching your criteria'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($penalties, Response::HTTP_OK);
    }

    /**
     * Отримати штраф за ID
     * 
     * @param string $id
     * @return JsonResponse
     */
    #[Route('/api/penalties/{id}', name: 'app_get_penalty_by_id', methods: ['GET'])]
    public function getPenaltyById(string $id): JsonResponse
    {
        /** @var Penalty|null $penalty */
        $penalty = $this->entityManager->getRepository(Penalty::class)->findOneBy(['id' => $id]);

        if (empty($penalty)) {
            return $this->json(['message' => 'Penalty not found'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($penalty, Response::HTTP_OK);
    }

    /**
     * Створити новий штраф
     * 
     * @param Request $request
     * @return JsonResponse
     */
    #[Route('/api/penalties', name: 'app_create_penalty', methods: ['POST'])]
    public function createPenalty(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (empty($data['reader_name'])) {
            return $this->json(['message' => 'Reader name is required'], Response::HTTP_BAD_REQUEST);
        }

        $penalty = new Penalty();
        $penalty->setReaderName($data['reader_name']);
        $penalty->setAmount($data['amount'] ?? 0);
        $penalty->setReason($data['reason'] ?? '');
        
        if (isset($data['is_paid'])) {
            $penalty->setIsPaid((bool) $data['is_paid']);
        }

        $this->entityManager->persist($penalty);
        $this->entityManager->flush();

        return $this->json($penalty, Response::HTTP_CREATED);
    }

    /**
     * Оновити дані штрафу
     * 
     * @param string $id
     * @param Request $request
     * @return JsonResponse
     */
    #[Route('/api/penalties/{id}', name: 'app_update_penalty', methods: ['PATCH', 'PUT'])]
    public function updatePenalty(string $id, Request $request): JsonResponse
    {
        /** @var Penalty|null $penalty */
        $penalty = $this->entityManager->getRepository(Penalty::class)->findOneBy(['id' => $id]);

        if (empty($penalty)) {
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

        $this->entityManager->flush();

        return $this->json($penalty, Response::HTTP_OK);
    }

    /**
     * Видалити штраф
     * 
     * @param string $id
     * @return JsonResponse
     */
    #[Route('/api/penalties/{id}', name: 'app_delete_penalty', methods: ['DELETE'])]
    public function deletePenalty(string $id): JsonResponse
    {
        /** @var Penalty|null $penalty */
        $penalty = $this->entityManager->getRepository(Penalty::class)->findOneBy(['id' => $id]);

        if (empty($penalty)) {
            return $this->json(['message' => 'Penalty not found'], Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($penalty);
        $this->entityManager->flush();

        return $this->json(['message' => 'Penalty deleted successfully'], Response::HTTP_OK);
    }
}