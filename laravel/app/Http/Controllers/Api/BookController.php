<?php

namespace App\Http\Controllers\Api;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class BookController extends ApiController
{
    /**
     * Отримати список книг з фільтрацією та пагінацією
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $exactFields = ['id', 'publisher_id']; 
        $partialFields = ['title', 'author'];

        return $this->respondWithPagination(
            Book::query(),
            $request,
            $exactFields,
            $partialFields
        );
    }

    /**
     * Отримати книгу за ID
     * @param string $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json(['error' => 'Книгу не знайдено'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($book, Response::HTTP_OK);
    }

    /**
     * Створити нову книгу
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

    
        $book = new Book();
        $book->title = $data['title'] ?? 'Невідома назва';
        $book->author = $data['author'] ?? 'Невідомий автор';
        $book->save();

        return response()->json([
            'message' => 'Книгу успішно додано',
            'book' => $book
        ], Response::HTTP_CREATED);
    }

    /**
     * Оновити книгу
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json(['error' => 'Книгу не знайдено'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['title'])) {
            $book->title = $data['title'];
        }
        if (isset($data['author'])) {
            $book->author = $data['author'];
        }
        $book->save();

        return response()->json([
            'message' => "Дані книги з ID $id оновлено",
            'updated_book' => $book
        ], Response::HTTP_OK);
    }

    /**
     * Видалити книгу
     * @param string $id
     * @return JsonResponse
     */
    public function destroy(string $id): JsonResponse
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json(['error' => 'Книгу не знайдено'], Response::HTTP_NOT_FOUND);
        }

        $book->delete();

        return response()->json([
            'message' => "Книгу з ID $id списано з бібліотеки"
        ], Response::HTTP_OK);
    }
}