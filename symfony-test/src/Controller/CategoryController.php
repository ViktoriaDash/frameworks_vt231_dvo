<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/categories', name: 'api_category_')]
class CategoryController extends AbstractController
{
    /**
     * Отримати список категорій з фільтрацією та пагінацією
     * 
     * @param Request $request
     * @param CategoryRepository $repository
     * @return JsonResponse
     */
    #[Route('', name: 'index', methods: ['GET'])]
    public function index(Request $request, CategoryRepository $repository): JsonResponse
    {
        $allowedParams = ['id', 'name', 'description'];

        try {
            $categories = $repository->filterAndPaginate($request, $allowedParams);
        } catch (\InvalidArgumentException $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        if (empty($categories['data'])) {
            return $this->json(['message' => 'Категорії не знайдені'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($categories, Response::HTTP_OK);
    }

    /**
     * Отримати категорію за ID
     * 
     * @param int $id
     * @param CategoryRepository $repository
     * @return JsonResponse
     */
    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(int $id, CategoryRepository $repository): JsonResponse
    {
        $category = $repository->find($id);

        if (!$category) {
            return $this->json(['message' => 'Категорію не знайдено'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($category, Response::HTTP_OK);
    }

    /**
     * Створити нову категорію
     * 
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return JsonResponse
     */
    #[Route('', name: 'store', methods: ['POST'])]
    public function store(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $category = new Category();
        $category->setName($data['name'] ?? '');
        
        if (isset($data['description'])) {
            $category->setDescription($data['description']);
        }

        $entityManager->persist($category);
        $entityManager->flush();

        return $this->json($category, Response::HTTP_CREATED);
    }

    /**
     * Оновити дані категорії
     * 
     * @param int $id
     * @param Request $request
     * @param CategoryRepository $repository
     * @param EntityManagerInterface $entityManager
     * @return JsonResponse
     */
    #[Route('/{id}', name: 'update', methods: ['PUT', 'PATCH'])]
    public function update(int $id, Request $request, CategoryRepository $repository, EntityManagerInterface $entityManager): JsonResponse
    {
        $category = $repository->find($id);

        if (!$category) {
            return $this->json(['message' => 'Категорію не знайдено'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['name'])) {
            $category->setName($data['name']);
        }
        if (array_key_exists('description', $data)) {
            $category->setDescription($data['description']);
        }

        $entityManager->flush();

        return $this->json($category, Response::HTTP_OK);
    }

    /**
     * Видалити категорію
     * 
     * @param int $id
     * @param CategoryRepository $repository
     * @param EntityManagerInterface $entityManager
     * @return JsonResponse
     */
    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function destroy(int $id, CategoryRepository $repository, EntityManagerInterface $entityManager): JsonResponse
    {
        $category = $repository->find($id);

        if (!$category) {
            return $this->json(['message' => 'Категорію не знайдено'], Response::HTTP_NOT_FOUND);
        }

        $entityManager->remove($category);
        $entityManager->flush();

        return $this->json(['message' => 'Категорію видалено'], Response::HTTP_NO_CONTENT);
    }
}