<?php

namespace App\Http\Controllers\Api;

use App\Models\Publisher;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class PublisherController extends ApiController
{
    /**
     * Отримати список видавництв з фільтрацією та пагінацією
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $exactFields = ['id']; 
        $partialFields = ['name', 'address', 'website'];

        return $this->respondWithPagination(
            Publisher::query(),
            $request,
            $exactFields,
            $partialFields
        );
    }

    /**
     * Отримати видавництво за ID
     * 
     * @param string $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        $publisher = Publisher::find($id);
        if (!$publisher) return response()->json(['error' => 'Видавництво не знайдено'], Response::HTTP_NOT_FOUND);
        return response()->json($publisher, Response::HTTP_OK);
    }

    /**
     * Створити нове видавництво
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $publisher = Publisher::create($request->all());
        return response()->json($publisher, Response::HTTP_CREATED);
    }

    /**
     * Оновити дані видавництва
     * 
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $publisher = Publisher::find($id);
        if (!$publisher) return response()->json(['error' => 'Видавництво не знайдено'], Response::HTTP_NOT_FOUND);
        
        $publisher->update($request->all());
        return response()->json($publisher, Response::HTTP_OK);
    }

    /**
     * Видалити видавництво
     * 
     * @param string $id
     * @return JsonResponse
     */
    public function destroy(string $id): JsonResponse
    {
        $publisher = Publisher::find($id);
        if (!$publisher) return response()->json(['error' => 'Видавництво не знайдено'], Response::HTTP_NOT_FOUND);
        
        $publisher->delete();
        return response()->json(['message' => 'Видавництво видалено'], Response::HTTP_OK);
    }
}