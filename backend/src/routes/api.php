<?php

use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

// ==========================================
// 1. RUTAS PÚBLICAS
// ==========================================
Route::prefix('/auth')->group(function () {
    Route::post('/registro', [AuthController::class, 'registro']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/verificar-email', [AuthController::class, 'verificarEmail']);
    Route::post('/reenviar-codigo', [AuthController::class, 'reenviarCodigo']);
});

Route::get('/categories', [CategoryController::class, 'index']);

// ==========================================
// 2. RUTAS PROTEGIDAS
// ==========================================
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    Route::prefix('/products')->group(function () {
        Route::post('/store', [ProductController::class, 'store']);
    });
});