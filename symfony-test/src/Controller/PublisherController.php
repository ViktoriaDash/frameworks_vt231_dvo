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

#[Route('/api/publishers', name: 'api_publisher_')]
class PublisherController extends AbstractController
{
    #[Route('', name: 'index', methods: ['GET'])]
    public function index(PublisherRepository $repository): JsonResponse
    {
        return $this->json($repository->findAll(), Response::HTTP_OK);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(int $id, PublisherRepository $repository): JsonResponse
    {
        $publisher = $repository->find($id);

        if (!$publisher) {
            return $this->json(['message' => 'Publisher not found'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($publisher, Response::HTTP_OK);
    }

    #[Route('', name: 'store', methods: ['POST'])]
    public function store(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $publisher = new Publisher();
        $publisher->setName($data['name'] ?? '');
        
        if (isset($data['address'])) {
            $publisher->setAddress($data['address']);
        }
        if (isset($data['website'])) {
            $publisher->setWebsite($data['website']);
        }

        $entityManager->persist($publisher);
        $entityManager->flush();

        return $this->json($publisher, Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'update', methods: ['PUT', 'PATCH'])]
    public function update(int $id, Request $request, PublisherRepository $repository, EntityManagerInterface $entityManager): JsonResponse
    {
        $publisher = $repository->find($id);

        if (!$publisher) {
            return $this->json(['message' => 'Publisher not found'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['name'])) {
            $publisher->setName($data['name']);
        }
        if (array_key_exists('address', $data)) {
            $publisher->setAddress($data['address']);
        }
        if (array_key_exists('website', $data)) {
            $publisher->setWebsite($data['website']);
        }

        $entityManager->flush();

        return $this->json($publisher, Response::HTTP_OK);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function destroy(int $id, PublisherRepository $repository, EntityManagerInterface $entityManager): JsonResponse
    {
        $publisher = $repository->find($id);

        if (!$publisher) {
            return $this->json(['message' => 'Publisher not found'], Response::HTTP_NOT_FOUND);
        }

        $entityManager->remove($publisher);
        $entityManager->flush();

        return $this->json(['message' => 'Publisher deleted successfully'], Response::HTTP_NO_CONTENT);
    }
}