<?php

namespace App\Controller;

use App\Entity\Publisher;
use App\Repository\PublisherRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class PublisherController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * @var PublisherRepository
     */
    private PublisherRepository $publisherRepository;

    public function __construct(EntityManagerInterface $entityManager, PublisherRepository $publisherRepository)
    {
        $this->entityManager = $entityManager;
        $this->publisherRepository = $publisherRepository;
    }

    /**
     * Отримати список видавництв з фільтрацією та пагінацією
     * @param Request $request
     * @return JsonResponse
     */
    #[Route('/api/publishers', name: 'app_get_publishers', methods: ['GET'])]
    public function getPublishers(Request $request): JsonResponse
    {
        $allowedParams = ['id', 'name', 'address', 'website'];

        // dd('Тест: запит успішно дійшов до контролера!'); // розкоментуй, якщо запит у Postman зависає

        try {
            $publishers = $this->publisherRepository->filterAndPaginate($request, $allowedParams);
        } catch (\InvalidArgumentException $e) {
            return $this->json(['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        if (empty($publishers['data']) && empty($publishers)) {
            return $this->json(['message' => 'No publishers found matching your criteria'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($publishers, Response::HTTP_OK);
    }

    /**
     * Отримати видавництво за ID
     * @param string $id
     * @return JsonResponse
     */
    #[Route('/api/publishers/{id}', name: 'app_get_publisher_by_id', methods: ['GET'])]
    public function getPublisherById(string $id): JsonResponse
    {
        /** @var Publisher|null $publisher */
        $publisher = $this->entityManager->getRepository(Publisher::class)->findOneBy(['id' => $id]);

        if (empty($publisher)) {
            return $this->json(['message' => 'Publisher not found'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($publisher, Response::HTTP_OK);
    }

    /**
     * Створити нове видавництво
     * @param Request $request
     * @return JsonResponse
     */
    #[Route('/api/publishers', name: 'app_create_publisher', methods: ['POST'])]
    public function createPublisher(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (empty($data['name'])) {
            return $this->json(['message' => 'Name is required'], Response::HTTP_BAD_REQUEST);
        }

        $publisher = new Publisher();
        $publisher->setName($data['name']);
        $publisher->setAddress($data['address'] ?? '');
        
        if (isset($data['website'])) {
            $publisher->setWebsite($data['website']);
        }

        $this->entityManager->persist($publisher);
        $this->entityManager->flush();

        return $this->json($publisher, Response::HTTP_CREATED);
    }

    /**
     * Оновити дані видавництва
     * @param string $id
     * @param Request $request
     * @return JsonResponse
     */
    #[Route('/api/publishers/{id}', name: 'app_update_publisher', methods: ['PATCH', 'PUT'])]
    public function updatePublisher(string $id, Request $request): JsonResponse
    {
        /** @var Publisher|null $publisher */
        $publisher = $this->entityManager->getRepository(Publisher::class)->findOneBy(['id' => $id]);

        if (empty($publisher)) {
            return $this->json(['message' => 'Publisher not found'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['name'])) {
            $publisher->setName($data['name']);
        }
        if (isset($data['address'])) {
            $publisher->setAddress($data['address']);
        }
        if (array_key_exists('website', $data)) {
            $publisher->setWebsite($data['website']);
        }

        $this->entityManager->flush();

        return $this->json($publisher, Response::HTTP_OK);
    }

    /**
     * Видалити видавництво
     * @param string $id
     * @return JsonResponse
     */
    #[Route('/api/publishers/{id}', name: 'app_delete_publisher', methods: ['DELETE'])]
    public function deletePublisher(string $id): JsonResponse
    {
        /** @var Publisher|null $publisher */
        $publisher = $this->entityManager->getRepository(Publisher::class)->findOneBy(['id' => $id]);

        if (empty($publisher)) {
            return $this->json(['message' => 'Publisher not found'], Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($publisher);
        $this->entityManager->flush();

        return $this->json(['message' => 'Publisher deleted successfully'], Response::HTTP_OK);
    }
}