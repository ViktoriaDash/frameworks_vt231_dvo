<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\PenaltyController;
use App\Http\Controllers\Api\PublisherController;
use App\Http\Controllers\Api\ReviewController;

Route::apiResource('books', BookController::class);
Route::apiResource('categories', CategoryController::class);
Route::apiResource('employees', EmployeeController::class);
Route::apiResource('penalties', PenaltyController::class);
Route::apiResource('publishers', PublisherController::class);
Route::apiResource('reviews', ReviewController::class);