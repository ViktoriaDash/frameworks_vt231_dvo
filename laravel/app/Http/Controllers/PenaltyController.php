<?php

namespace App\Http\Controllers;

use App\Models\Penalty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class PenaltyController extends Controller
{
    /**
     * Отримати штрафи
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $penalties = Penalty::all();

        return response()->json($penalties, Response::HTTP_OK);
    }

    /**
     * Отримати за id
     * @param string $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        $penalty = Penalty::find($id);

        if (empty($penalty)) {
            return response()->json(
                ['message' => 'Penalty not found'],
                Response::HTTP_NOT_FOUND
            );
        }

        return response()->json($penalty, Response::HTTP_OK);
    }

    /**
     * Створити штраф
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $penalty = DB::transaction(function () use ($data) {
            $penalty = new Penalty();
            $penalty->fill($data);
            $penalty->save();

            return $penalty;
        });

        return new JsonResponse($penalty, Response::HTTP_CREATED);
    }

    /**
     * Змінити штраф
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $penalty = Penalty::find($id);

        if (empty($penalty)) {
            return response()->json(
                ['message' => 'Penalty not found'],
                Response::HTTP_NOT_FOUND
            );
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['reader_name'])) {
            $penalty->reader_name = $data['reader_name'];
        }
        if (isset($data['amount'])) {
            $penalty->amount = $data['amount'];
        }
        if (isset($data['reason'])) {
            $penalty->reason = $data['reason'];
        }
        if (array_key_exists('is_paid', $data)) {
            $penalty->is_paid = $data['is_paid'];
        }

        $penalty->save();

        return new JsonResponse($penalty, Response::HTTP_OK);
    }

    /**
     * Видалити штраф
     * @param string $id
     * @return JsonResponse
     */
    public function destroy(string $id): JsonResponse
    {
        $penalty = Penalty::find($id);

        if (empty($penalty)) {
            return response()->json(
                ['message' => 'Penalty not found'],
                Response::HTTP_NOT_FOUND
            );
        }

        $penalty->delete();

        return new JsonResponse(['message' => 'Penalty deleted successfully'], Response::HTTP_NO_CONTENT);
    }
}