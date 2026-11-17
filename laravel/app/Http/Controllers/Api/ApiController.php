<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class ApiController extends Controller
{
    /**
     * Унікальний метод для фільтрації та пагінації.
     * 
     * @param Builder $query Початковий запит до БД
     * @param Request $request Об'єкт запиту
     * @param array $exactFields Поля для точного збігу (id, year тощо)
     * @param array $partialFields Поля для часткового збігу (LIKE)
     * @return JsonResponse
     */
    protected function respondWithPagination(
        Builder $query,
        Request $request,
        array $exactFields = [],
        array $partialFields = []
    ): JsonResponse {
        $allowedParams = array_merge($exactFields, $partialFields, ['page', 'itemsPerPage']);
        $queryParams = $request->query();

        foreach ($queryParams as $key => $value) {
            $baseKey = preg_replace('/_(from|to)$/', '', $key);

            if (!in_array($baseKey, $allowedParams, true)) {
                return response()->json(
                    ['error' => "Параметр '{$key}' не підтримується для пошуку."],
                    Response::HTTP_BAD_REQUEST
                );
            }

            if (in_array($key, ['page', 'itemsPerPage'])) {
                continue;
            }

            if ($value !== null && $value !== '') {
                if (str_ends_with($key, '_from')) {
                    $query->where($baseKey, '>=', $value);
                } elseif (str_ends_with($key, '_to')) {
                    $query->where($baseKey, '<=', $value);
                } else {
                    // Звичайна фільтрація (LIKE або точний збіг)
                    if (in_array($key, $partialFields, true)) {
                        $query->where($key, 'LIKE', "%{$value}%");
                    } else {
                        $query->where($key, '=', $value);
                    }
                }
            }
        }

        $perPage = max(1, min($request->query('itemsPerPage', 15), 100));

        $result = $query->paginate($perPage)->appends($request->query());

        if ($result->isEmpty()) {
            return response()->json(
                ['message' => 'За вашим запитом нічого не знайдено'], 
                Response::HTTP_NOT_FOUND
            );
        }

        return response()->json($result, Response::HTTP_OK);
    }
}