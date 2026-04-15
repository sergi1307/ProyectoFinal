<?php


use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\IncidentController;
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

Route::get('/products', [ProductController::class, 'index']); 
Route::get('/categories', [CategoryController::class, 'index']);

// ==========================================
// 2. RUTAS PROTEGIDAS
// ==========================================
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    Route::prefix('/products')->group(function () {
        Route::get('/me', [ProductController::class, 'me']);
        Route::get('/index', [ProductController::class, 'index']);
        Route::get('/favorites', [ProductController::class, 'getFavorites']);
        Route::post('/store', [ProductController::class, 'store']);
    });

    Route::post('/products/{product}/favorite', [ProductController::class, 'toggleFavorite']);

    Route::post('/incidents', [IncidentController::class, 'store']);
    Route::get('/incidents', [IncidentController::class, 'index']);
    Route::put('/incidents/{id}', [IncidentController::class, 'update']);
});