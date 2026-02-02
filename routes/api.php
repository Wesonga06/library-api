<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BookController;


Route::get('/books', function () {
    return response()->json([
        'message' => 'API is working',
]);
});

Route::get('/books', [BookController::class, 'index']);
Route::post('/books', [BookController::class, 'store']);

// Retrieve a specific book by ID
Route::get('/books/{id}', [BookController::class, 'show']);

// Update a specific book by ID
Route::put('/books/{id}', [BookController::class, 'update']);

// Delete a specific book by ID
Route::delete('/books/{id}', [BookController::class, 'destroy']);  