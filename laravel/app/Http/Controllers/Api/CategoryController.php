<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends ApiController
{
    /**
     * Отримати список категорій з фільтрацією та пагінацією
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $exactFields = ['id']; 
$partialFields = ['name', 'description'];

        return $this->respondWithPagination(
            Category::query(),
            $request,
            $exactFields,$partialFields
        );
    }

    /**
     * Отримати категорію за ID
     * 
     * @param string $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        $category = Category::find($id);
        if (!$category) return response()->json(['error' => 'Категорію не знайдено'], Response::HTTP_NOT_FOUND);
        return response()->json($category, Response::HTTP_OK);
    }

    /**
     * Створити нову категорію
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $category = Category::create($request->all());
        return response()->json($category, Response::HTTP_CREATED);
    }

    /**
     * Оновити дані категорії
     * 
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(Request $request, string$id): JsonResponse
    {
        $category = Category::find($id);
        if (!$category) return response()->json(['error' => 'Категорію не знайдено'], Response::HTTP_NOT_FOUND);
        
        $category->update($request->all());
        return response()->json($category, Response::HTTP_OK);
    }

    /**
     * Видалити категорію
     * 
     * @param string $id
     * @return JsonResponse
     */
    public function destroy(string $id): JsonResponse
    {
        $category = Category::find($id);
        if (!$category) return response()->json(['error' => 'Категорію не знайдено'], Response::HTTP_NOT_FOUND);
        
        $category->delete();
        return response()->json(['message' => 'Категорію видалено'], Response::HTTP_OK);
    }
}