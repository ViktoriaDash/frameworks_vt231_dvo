<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\PenaltyController;
use App\Http\Controllers\PublisherController;
use App\Http\Controllers\ReviewController;

Route::resource('categories', CategoryController::class)->except(['create', 'edit']);
Route::resource('employees', EmployeeController::class)->except(['create', 'edit']);
Route::resource('penalties', PenaltyController::class)->except(['create', 'edit']);
Route::resource('publishers', PublisherController::class)->except(['create', 'edit']);
Route::resource('reviews', ReviewController::class)->except(['create', 'edit']);