<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;
use App\Http\Controller\BookController;
Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', [TestController::class, 'test']);

Route::get('/books', [BookController::class, 'getBooks']);
Route::get('/books/{id}', [BookController::class, 'getBookItem']);
Route::post('/books', [BookController::class, 'createBook']);
Route::put('/books/{id}', [BookController::class, 'updateBook']);
Route::delete('/books/{id}', [BookController::class, 'deleteBook']);