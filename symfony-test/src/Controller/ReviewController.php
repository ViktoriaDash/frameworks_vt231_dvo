<?php

namespace App\Controller;

use App\Entity\Review;
use App\Repository\ReviewRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/reviews', name: 'api_review_')]
class ReviewController extends AbstractController
{
    /**
     * Отримати список відгуків з фільтрацією та пагінацією
     * 
     * @param Request $request
     * @param ReviewRepository $repository
     * @return JsonResponse
     */
    #[Route('', name: 'index', methods: ['GET'])]
    public function index(Request $request, ReviewRepository $repository): JsonResponse
    {
        $allowedParams = ['id', 'reader_name', 'content', 'rating'];

        try {
            $reviews = $repository->filterAndPaginate($request, $allowedParams);
        } catch (\InvalidArgumentException $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        if (empty($reviews['data'])) {
            return $this->json(['message' => 'Відгуки не знайдено'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($reviews, Response::HTTP_OK);
    }

    /**
     * Отримати відгук за ID
     * 
     * @param int $id
     * @param ReviewRepository $repository
     * @return JsonResponse
     */
    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(int $id, ReviewRepository $repository): JsonResponse
    {
        $review = $repository->find($id);

        if (!$review) {
            return $this->json(['message' => 'Відгук не знайдено'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($review, Response::HTTP_OK);
    }

    /**
     * Створити новий відгук
     * 
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return JsonResponse
     */
    #[Route('', name: 'store', methods: ['POST'])]
    public function store(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $review = new Review();
        $review->setReaderName($data['reader_name'] ?? '');
        $review->setContent($data['content'] ?? '');
        $review->setRating($data['rating'] ?? 1);

        $entityManager->persist($review);
        $entityManager->flush();

        return $this->json($review, Response::HTTP_CREATED);
    }

    /**
     * Оновити дані відгуку
     * 
     * @param int $id
     * @param Request $request
     * @param ReviewRepository $repository
     * @param EntityManagerInterface $entityManager
     * @return JsonResponse
     */
    #[Route('/{id}', name: 'update', methods: ['PUT', 'PATCH'])]
    public function update(int $id, Request $request, ReviewRepository $repository, EntityManagerInterface $entityManager): JsonResponse
    {
        $review = $repository->find($id);

        if (!$review) {
            return $this->json(['message' => 'Відгук не знайдено'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['reader_name'])) {
            $review->setReaderName($data['reader_name']);
        }
        if (isset($data['content'])) {
            $review->setContent($data['content']);
        }
        if (isset($data['rating'])) {
            $review->setRating($data['rating']);
        }

        $entityManager->flush();

        return $this->json($review, Response::HTTP_OK);
    }

    /**
     * Видалити відгук
     * 
     * @param int $id
     * @param ReviewRepository $repository
     * @param EntityManagerInterface $entityManager
     * @return JsonResponse
     */
    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function destroy(int $id, ReviewRepository $repository, EntityManagerInterface $entityManager): JsonResponse
    {
        $review = $repository->find($id);

        if (!$review) {
            return $this->json(['message' => 'Відгук не знайдено'], Response::HTTP_NOT_FOUND);
        }

        $entityManager->remove($review);
        $entityManager->flush();

        return $this->json(['message' => 'Відгук видалено'], Response::HTTP_NO_CONTENT);
    }
}