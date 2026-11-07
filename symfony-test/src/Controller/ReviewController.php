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
     * Отримати всі відгуки
     */
    #[Route('', name: 'index', methods: ['GET'])]
    public function index(ReviewRepository $repository): JsonResponse
    {
        return $this->json($repository->findAll(), Response::HTTP_OK);
    }

    /**
     * Отримати відгук за ID
     */
    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(int $id, ReviewRepository $repository): JsonResponse
    {
        $review = $repository->find($id);

        if (!$review) {
            return $this->json(['message' => 'Review not found'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($review, Response::HTTP_OK);
    }

    /**
     * Створити відгук
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
     * Оновити відгук
     */
    #[Route('/{id}', name: 'update', methods: ['PUT', 'PATCH'])]
    public function update(int $id, Request $request, ReviewRepository $repository, EntityManagerInterface $entityManager): JsonResponse
    {
        $review = $repository->find($id);

        if (!$review) {
            return $this->json(['message' => 'Review not found'], Response::HTTP_NOT_FOUND);
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
     */
    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function destroy(int $id, ReviewRepository $repository, EntityManagerInterface $entityManager): JsonResponse
    {
        $review = $repository->find($id);

        if (!$review) {
            return $this->json(['message' => 'Review not found'], Response::HTTP_NOT_FOUND);
        }

        $entityManager->remove($review);
        $entityManager->flush();

        return $this->json(['message' => 'Review deleted successfully'], Response::HTTP_NO_CONTENT);
    }
}