<?php

namespace App\Http\Controllers\Api;

use App\Models\Penalty;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class PenaltyController extends ApiController
{
    /**
     * Отримати список штрафів з фільтрацією та пагінацією
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $exactFields = ['id', 'amount', 'is_paid']; 
        $partialFields = ['reader_name', 'reason'];

        return $this->respondWithPagination(
            Penalty::query(),
            $request,
            $exactFields,
            $partialFields
        );
    }

    /**
     * Отримати штраф за ID
     * 
     * @param string $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        $penalty = Penalty::find($id);
        if (!$penalty) return response()->json(['error' => 'Штраф не знайдено'], Response::HTTP_NOT_FOUND);
        return response()->json($penalty, Response::HTTP_OK);
    }

    /**
     * Створити новий штраф
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $penalty = Penalty::create($request->all());
        return response()->json($penalty, Response::HTTP_CREATED);
    }

    /**
     * Оновити дані штрафу
     * 
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $penalty = Penalty::find($id);
        if (!$penalty) return response()->json(['error' => 'Штраф не знайдено'], Response::HTTP_NOT_FOUND);
        
        $penalty->update($request->all());
        return response()->json($penalty, Response::HTTP_OK);
    }

    /**
     * Видалити штраф
     * 
     * @param string $id
     * @return JsonResponse
     */
    public function destroy(string $id): JsonResponse
    {
        $penalty = Penalty::find($id);
        if (!$penalty) return response()->json(['error' => 'Штраф не знайдено'], Response::HTTP_NOT_FOUND);
        
        $penalty->delete();
        return response()->json(['message' => 'Штраф видалено'], Response::HTTP_OK);
    }
}