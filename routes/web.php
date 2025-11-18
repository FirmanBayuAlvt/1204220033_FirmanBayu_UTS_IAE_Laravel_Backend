<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;

// ✅ ROUTES TANPA CSRF PROTECTION
Route::withoutMiddleware(['web'])->group(function () {
    Route::prefix('api')->group(function () {

        Route::get('/health', function () {
            return response()->json(['status' => 'success', 'message' => 'API is running']);
        });

        Route::get('/test', function () {
            return response()->json(['status' => 'success', 'message' => 'Test works']);
        });

        // ✅ BOOK ROUTES - TANPA CSRF
        Route::get('/books', [BookController::class, 'index']);
        Route::post('/books', [BookController::class, 'store']);
        Route::get('/books/{id}', [BookController::class, 'show']);
        Route::put('/books/{id}', [BookController::class, 'update']);
        Route::delete('/books/{id}', [BookController::class, 'destroy']);
    });
});
