<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ReviewController extends Controller
{
    /**
     * Отримати відгуки
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $reviews = Review::all();

        return response()->json($reviews, Response::HTTP_OK);
    }

    /**
     * Отримати за id
     * @param string $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        $review = Review::find($id);

        if (empty($review)) {
            return response()->json(
                ['message' => 'Review not found'],
                Response::HTTP_NOT_FOUND
            );
        }

        return response()->json($review, Response::HTTP_OK);
    }

    /**
     * Створити відгук
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $review = DB::transaction(function () use ($data) {
            $review = new Review();
            $review->fill($data);
            $review->save();

            return $review;
        });

        return new JsonResponse($review, Response::HTTP_CREATED);
    }

    /**
     * Змінити відгук
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $review = Review::find($id);

        if (empty($review)) {
            return response()->json(
                ['message' => 'Review not found'],
                Response::HTTP_NOT_FOUND
            );
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['reader_name'])) {
            $review->reader_name = $data['reader_name'];
        }
        if (isset($data['content'])) {
            $review->content = $data['content'];
        }
        if (isset($data['rating'])) {
            $review->rating = $data['rating'];
        }

        $review->save();

        return new JsonResponse($review, Response::HTTP_OK);
    }

    /**
     * Видалити відгук
     * @param string $id
     * @return JsonResponse
     */
    public function destroy(string $id): JsonResponse
    {
        $review = Review::find($id);

        if (empty($review)) {
            return response()->json(
                ['message' => 'Review not found'],
                Response::HTTP_NOT_FOUND
            );
        }

        $review->delete();

        return new JsonResponse(['message' => 'Review deleted successfully'], Response::HTTP_NO_CONTENT);
    }
}