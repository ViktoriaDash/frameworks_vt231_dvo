<?php

namespace App\Http\Controllers; // Залишаємо цей простір імен, щоб не зламати твої маршрути у web.php

use App\Models\Publisher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class PublisherController extends Controller
{
    /**
     * Отримати видавництва
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $publishers = Publisher::all();

        return response()->json($publishers, Response::HTTP_OK);
    }

    /**
     * Отримати за id
     * @param string $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        $publisher = Publisher::find($id);

        if (empty($publisher)) {
            return response()->json(
                ['message' => 'Publisher not found'],
                Response::HTTP_NOT_FOUND
            );
        }

        return response()->json($publisher, Response::HTTP_OK);
    }

    /**
     * Створити видавництво
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $publisher = DB::transaction(function () use ($data) {
            $publisher = new Publisher();
            $publisher->fill($data);
            $publisher->save();

            return $publisher;
        });

        return new JsonResponse($publisher, Response::HTTP_CREATED);
    }

    /**
     * Змінити видавництво
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $publisher = Publisher::find($id);

        if (empty($publisher)) {
            return response()->json(
                ['message' => 'Publisher not found'],
                Response::HTTP_NOT_FOUND
            );
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['name'])) {
            $publisher->name = $data['name'];
        }

        if (array_key_exists('address', $data)) {
            $publisher->address = $data['address'];
        }

        if (array_key_exists('website', $data)) {
            $publisher->website = $data['website'];
        }

        $publisher->save();

        return new JsonResponse($publisher, Response::HTTP_OK);
    }

    /**
     * Видалити видавництво
     * @param string $id
     * @return JsonResponse
     */
    public function destroy(string $id): JsonResponse
    {
        $publisher = Publisher::find($id);

        if (empty($publisher)) {
            return response()->json(
                ['message' => 'Publisher not found'],
                Response::HTTP_NOT_FOUND
            );
        }

        $publisher->delete();

        return new JsonResponse(['message' => 'Publisher deleted successfully'], Response::HTTP_OK);
    }
}