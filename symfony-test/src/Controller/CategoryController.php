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
     * Отримати всі категорії
     */
    #[Route('', name: 'index', methods: ['GET'])]
    public function index(CategoryRepository $repository): JsonResponse
    {
        $categories = $repository->findAll();
        
        return $this->json($categories, Response::HTTP_OK);
    }

    /**
     * Отримати категорію за ID
     */
    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(int $id, CategoryRepository $repository): JsonResponse
    {
        $category = $repository->find($id);

        if (!$category) {
            return $this->json(['message' => 'Category not found'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($category, Response::HTTP_OK);
    }

    /**
     * Створити категорію
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
     * Оновити категорію
     */
    #[Route('/{id}', name: 'update', methods: ['PUT', 'PATCH'])]
    public function update(int $id, Request $request, CategoryRepository $repository, EntityManagerInterface $entityManager): JsonResponse
    {
        $category = $repository->find($id);

        if (!$category) {
            return $this->json(['message' => 'Category not found'], Response::HTTP_NOT_FOUND);
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
     */
    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function destroy(int $id, CategoryRepository $repository, EntityManagerInterface $entityManager): JsonResponse
    {
        $category = $repository->find($id);

        if (!$category) {
            return $this->json(['message' => 'Category not found'], Response::HTTP_NOT_FOUND);
        }

        $entityManager->remove($category);
        $entityManager->flush();

        return $this->json(['message' => 'Category deleted successfully'], Response::HTTP_NO_CONTENT);
    }
}