<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\AuthController; // Assuming you have an AuthController for login/register

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// 1. A quick test route to verify the API is online
Route::get('/test', function () {
    return response()->json(['message' => 'API is working']);
});

// 2. Public Authentication Routes (No token required)
// These match the fetch() requests in your app.js
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// 3. Protected Routes (Require a valid Bearer token from login)
Route::middleware('auth:sanctum')->group(function () {
    
    // Get the currently logged-in user's info (Standard Laravel route)
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // This single line automatically creates all 5 routes for your BookController:
    // GET /api/books (index)
    // POST /api/books (store)
    // GET /api/books/{id} (show)
    // PUT /api/books/{id} (update)
    // DELETE /api/books/{id} (destroy)
    Route::apiResource('books', BookController::class);
    
    // Optional: Logout route if you want to invalidate the token later
    Route::post('/logout', [AuthController::class, 'logout']);
});