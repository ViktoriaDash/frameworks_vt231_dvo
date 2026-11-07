<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class EmployeeController extends Controller
{
    /**
     * Отримати працівників
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $employees = Employee::all();

        return response()->json($employees, Response::HTTP_OK);
    }

    /**
     * Отримати за id
     * @param string $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        $employee = Employee::find($id);

        if (empty($employee)) {
            return response()->json(
                ['message' => 'Employee not found'],
                Response::HTTP_NOT_FOUND
            );
        }

        return response()->json($employee, Response::HTTP_OK);
    }

    /**
     * Створити працівника
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $employee = DB::transaction(function () use ($data) {
            $employee = new Employee();
            $employee->fill($data);
            $employee->save();

            return $employee;
        });

        return new JsonResponse($employee, Response::HTTP_CREATED);
    }

    /**
     * Змінити працівника
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $employee = Employee::find($id);

        if (empty($employee)) {
            return response()->json(
                ['message' => 'Employee not found'],
                Response::HTTP_NOT_FOUND
            );
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['name'])) {
            $employee->name = $data['name'];
        }
        if (isset($data['position'])) {
            $employee->position = $data['position'];
        }
        if (array_key_exists('phone', $data)) {
            $employee->phone = $data['phone'];
        }

        $employee->save();

        return new JsonResponse($employee, Response::HTTP_OK);
    }

    /**
     * Видалити працівника
     * @param string $id
     * @return JsonResponse
     */
    public function destroy(string $id): JsonResponse
    {
        $employee = Employee::find($id);

        if (empty($employee)) {
            return response()->json(
                ['message' => 'Employee not found'],
                Response::HTTP_NOT_FOUND
            );
        }

        $employee->delete();

        return new JsonResponse(['message' => 'Employee deleted successfully'], Response::HTTP_NO_CONTENT);
    }
}