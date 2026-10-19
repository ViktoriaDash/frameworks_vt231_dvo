<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class BookController extends Controller
{
    /**
     * Масив книг (імітація бази даних бібліотеки)
     */
    private array $books = [
        1 => ['id' => 1, 'title' => '1984', 'author' => 'Джордж Оруелл'],
        2 => ['id' => 2, 'title' => 'Кобзар', 'author' => 'Тарас Шевченко'],
    ];

    // 1. Отримати список усіх книг (Read)
    public function getBooks(): JsonResponse
    {
        return response()->json(array_values($this->books), Response::HTTP_OK);
    }

    // 2. Отримати одну книгу за ID (Read)
    public function getBookItem(int $id): JsonResponse
    {
        if (!isset($this->books[$id])) {
            return response()->json(['error' => 'Книгу не знайдено в бібліотеці'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($this->books[$id], Response::HTTP_OK);
    }

    // 3. Додати нову книгу (Create)
    public function createBook(Request $request): JsonResponse
    {
        // Отримуємо дані з класу Request, як просив викладач
        $data = json_decode($request->getContent(), true);

        $lastId = !empty($this->books) ? max(array_keys($this->books)) : 0;
        $newId = $lastId + 1;

        $newBook = [
            'id' => $newId,
            'title' => $data['title'] ?? 'Невідома назва',
            'author' => $data['author'] ?? 'Невідомий автор',
        ];

        $this->books[$newId] = $newBook;

        return response()->json([
            'message' => 'Книгу успішно додано',
            'book' => $newBook,
            'all_books' => array_values($this->books)
        ], Response::HTTP_CREATED);
    }

    // 4. Оновити дані книги (Update)
    public function updateBook(Request $request, int $id): JsonResponse
    {
        if (!isset($this->books[$id])) {
            return response()->json(['error' => 'Книгу не знайдено'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['title'])) {
            $this->books[$id]['title'] = $data['title'];
        }
        if (isset($data['author'])) {
            $this->books[$id]['author'] = $data['author'];
        }

        return response()->json([
            'message' => "Дані книги з ID $id оновлено",
            'updated_book' => $this->books[$id]
        ], Response::HTTP_OK);
    }

    // 5. Видалити книгу (Delete)
    public function deleteBook(int $id): JsonResponse
    {
        if (!isset($this->books[$id])) {
            return response()->json(['error' => 'Книгу не знайдено'], Response::HTTP_NOT_FOUND);
        }

        unset($this->books[$id]);

        return response()->json([
            'message' => "Книгу з ID $id списано з бібліотеки",
            'remaining_books' => array_values($this->books)
        ], Response::HTTP_OK);
    }
}