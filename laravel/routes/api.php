<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\PenaltyController;
use App\Http\Controllers\Api\PublisherController;
use App\Http\Controllers\Api\ReviewController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:login');
Route::post('/token/refresh', [AuthController::class, 'refresh']);

Route::middleware('auth:api')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::middleware('can:client')->group(function () {
        Route::apiResource('books', BookController::class)->only(['index', 'show']);
        Route::apiResource('categories', CategoryController::class)->only(['index', 'show']);
        Route::apiResource('employees', EmployeeController::class)->only(['index', 'show']);
        Route::apiResource('penalties', PenaltyController::class)->only(['index', 'show']);
        Route::apiResource('publishers', PublisherController::class)->only(['index', 'show']);
        Route::apiResource('reviews', ReviewController::class)->only(['index', 'show']);
    });

    Route::middleware('can:manager')->group(function () {
        Route::apiResource('books', BookController::class)->only(['store', 'update']);
        Route::apiResource('categories', CategoryController::class)->only(['store', 'update']);
        Route::apiResource('employees', EmployeeController::class)->only(['store', 'update']);
        Route::apiResource('penalties', PenaltyController::class)->only(['store', 'update']);
        Route::apiResource('publishers', PublisherController::class)->only(['store', 'update']);
        Route::apiResource('reviews', ReviewController::class)->only(['store', 'update']);
    });

    Route::middleware('can:admin')->group(function () {
        Route::apiResource('books', BookController::class)->only(['destroy']);
        Route::apiResource('categories', CategoryController::class)->only(['destroy']);
        Route::apiResource('employees', EmployeeController::class)->only(['destroy']);
        Route::apiResource('penalties', PenaltyController::class)->only(['destroy']);
        Route::apiResource('publishers', PublisherController::class)->only(['destroy']);
        Route::apiResource('reviews', ReviewController::class)->only(['destroy']);
    });
});