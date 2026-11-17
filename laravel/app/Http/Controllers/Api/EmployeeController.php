<?php

namespace App\Http\Controllers\Api;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class EmployeeController extends ApiController
{
    /**
     * Отримати список працівників з фільтрацією та пагінацією
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $exactFields = ['id']; 
        $partialFields = ['name', 'position', 'phone'];

        return $this->respondWithPagination(
            Employee::query(),
            $request,
            $exactFields,
            $partialFields
        );
    }

    /**
     * Отримати працівника за ID
     * 
     * @param string $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        $employee = Employee::find($id);
        if (!$employee) return response()->json(['error' => 'Працівника не знайдено'], Response::HTTP_NOT_FOUND);
        return response()->json($employee, Response::HTTP_OK);
    }

    /**
     * Створити нового працівника
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $employee = Employee::create($request->all());
        return response()->json($employee, Response::HTTP_CREATED);
    }

    /**
     * Оновити дані працівника
     * 
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $employee = Employee::find($id);
        if (!$employee) return response()->json(['error' => 'Працівника не знайдено'], Response::HTTP_NOT_FOUND);
        
        $employee->update($request->all());
        return response()->json($employee, Response::HTTP_OK);
    }

    /**
     * Видалити працівника
     * 
     * @param string $id
     * @return JsonResponse
     */
    public function destroy(string $id): JsonResponse
    {
        $employee = Employee::find($id);
        if (!$employee) return response()->json(['error' => 'Працівника не знайдено'], Response::HTTP_NOT_FOUND);
        
        $employee->delete();
        return response()->json(['message' => 'Працівника видалено'], Response::HTTP_OK);
    }
}