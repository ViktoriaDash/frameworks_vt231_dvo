<?php

namespace App\Http\Controllers\Api;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ReviewController extends ApiController
{
    /**
     * Отримати список відгуків з фільтрацією та пагінацією
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $exactFields = ['id', 'rating']; 
        $partialFields = ['reader_name', 'content'];

        return $this->respondWithPagination(
            Review::query(),
            $request,
            $exactFields,
            $partialFields
        );
    }

    /**
     * Отримати відгук за ID
     * 
     * @param string $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        $review = Review::find($id);

        if (!$review) {
            return response()->json(
                ['message' => 'Відгук не знайдено'],
                Response::HTTP_NOT_FOUND
            );
        }

        return response()->json($review, Response::HTTP_OK);
    }

    /**
     * Створити новий відгук
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $review = Review::create($request->all());
        
        return response()->json($review, Response::HTTP_CREATED);
    }

    /**
     * Змінити дані відгуку
     * 
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $review = Review::find($id);

        if (!$review) {
            return response()->json(
                ['message' => 'Відгук не знайдено'],
                Response::HTTP_NOT_FOUND
            );
        }

        $review->update($request->all());
        
        return response()->json($review, Response::HTTP_OK);
    }

    /**
     * Видалити відгук
     * 
     * @param string $id
     * @return JsonResponse
     */
    public function destroy(string $id): JsonResponse
    {
        $review = Review::find($id);

        if (!$review) {
            return response()->json(
                ['message' => 'Відгук не знайдено'],
                Response::HTTP_NOT_FOUND
            );
        }

        $review->delete();

        return response()->json(['message' => 'Відгук успішно видалено'], Response::HTTP_OK);
    }
}