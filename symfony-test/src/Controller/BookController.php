<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BookController extends AbstractController
{
  /**
   * Масив книг (імітація бази даних бібліотеки)
   * @var array|array[]
   */
  private array $books = [
    1 => ['id' => 1, 'title' => '1984', 'author' => 'Джордж Оруелл'],
    2 => ['id' => 2, 'title' => 'Кобзар', 'author' => 'Тарас Шевченко'],
  ];

  /**
   * Отримати список усіх книг
   * @return JsonResponse
   */
  #[Route('/books', name: 'get_books', methods: ['GET'])]
  public function getBooks(): JsonResponse
  {
    return new JsonResponse(array_values($this->books), Response::HTTP_OK);
  }

  /**
   * Отримати одну книгу за її ID
   * @param int $id
   * @return JsonResponse
   */
  #[Route('/books/{id}', name: 'get_book_item', methods: ['GET'])]
  public function getBookItem(int $id): JsonResponse
  {
    if (!isset($this->books[$id])) {
      return new JsonResponse(['error' => 'Книгу не знайдено в бібліотеці'], Response::HTTP_NOT_FOUND);
    }

    return new JsonResponse($this->books[$id], Response::HTTP_OK);
  }

  /**
   * Додати нову книгу в бібліотеку
   * @param Request $request
   * @return JsonResponse
   */
  #[Route('/books', name: 'create_book', methods: ['POST'])]
  public function createBook(Request $request): JsonResponse
  {
    $data = json_decode($request->getContent(), true); // Отримуємо дані з класу Request[cite: 8, 9]

    $lastId = !empty($this->books) ? max(array_keys($this->books)) : 0;
    $newId = $lastId + 1;

    $newBook = [
      'id' => $newId,
      'title' => $data['title'] ?? 'Невідома назва',
      'author' => $data['author'] ?? 'Невідомий автор',
    ];

    $this->books[$newId] = $newBook;

    return new JsonResponse([
      'message' => 'Книгу успішно додано',
      'book' => $newBook,
      'all_books' => array_values($this->books)
    ], Response::HTTP_CREATED);
  }

  /**
   * Оновити дані наявної книги (наприклад, виправити назву чи автора)
   * @param int $id
   * @param Request $request
   * @return JsonResponse
   */
  #[Route('/books/{id}', name: 'update_book', methods: ['PUT', 'PATCH'])]
  public function updateBook(int $id, Request $request): JsonResponse
  {
    if (!isset($this->books[$id])) {
      return new JsonResponse(['error' => 'Книгу не знайдено'], Response::HTTP_NOT_FOUND);
    }

    $data = json_decode($request->getContent(), true);

    if (isset($data['title'])) {
      $this->books[$id]['title'] = $data['title'];
    }
    if (isset($data['author'])) {
      $this->books[$id]['author'] = $data['author'];
    }

    return new JsonResponse([
      'message' => "Дані книги з ID $id оновлено",
      'updated_book' => $this->books[$id]
    ], Response::HTTP_OK);
  }

  /**
   * Видалити книгу з бібліотеки
   * @param int $id
   * @return JsonResponse
   */
  #[Route('/books/{id}', name: 'delete_book', methods: ['DELETE'])]
  public function deleteBook(int $id): JsonResponse
  {
    if (!isset($this->books[$id])) {
      return new JsonResponse(['error' => 'Книгу не знайдено'], Response::HTTP_NOT_FOUND);
    }

    unset($this->books[$id]);

    return new JsonResponse([
      'message' => "Книгу з ID $id списано з бібліотеки",
      'remaining_books' => array_values($this->books)
    ], Response::HTTP_OK);
  }
}