<?php

use App\Http\Controllers\AdminController;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\IncidentController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SellerController;
use Illuminate\Support\Facades\Route;

// ==========================================
// 1. RUTAS PÚBLICAS
// ==========================================
Route::prefix('/auth')->group(function () {
    Route::post('/registro', [AuthController::class, 'registro']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);
    Route::post('/verificar-email', [AuthController::class, 'verificarEmail']);
    Route::post('/reenviar-codigo', [AuthController::class, 'reenviarCodigo']);
});

Route::get('/products', [ProductController::class, 'index']);
Route::get('/categories', [CategoryController::class, 'index']);

// ==========================================
// 2. RUTAS PROTEGIDAS (Usuarios y Admins)
// ==========================================
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::get('/users', [AuthController::class, 'index']);
    Route::post('/users/{id}/toggle-status', [AuthController::class, 'toggleStatus']);

    Route::post('/auth/logout', [AuthController::class, 'logout']);

    Route::prefix('/products')->group(function () {
        Route::get('/me', [ProductController::class, 'me']);
        Route::get('/index', [ProductController::class, 'index']);
        Route::get('/favorites', [ProductController::class, 'getFavorites']);
        Route::post('/store', [ProductController::class, 'store']);
        Route::get('/destacados', [ProductController::class, 'destacados']);
        Route::get('/tendencias', [ProductController::class, 'tendencias']);
        Route::get('/{id}', [ProductController::class, 'show']);
    });

    Route::prefix('/seller')->group(function () {
        Route::get('/balance', [SellerController::class, 'obtenerBalances']);
    });

    Route::prefix('/cart')->group(function () {
        Route::get('/', [CartController::class, 'index']);
        Route::post('/', [CartController::class, 'store']);
        Route::put('/{id}', [CartController::class, 'update']);
        Route::delete('/{id}', [CartController::class, 'destroy']);
    });

    Route::prefix('/paypal')->group(function () {
        Route::post('/create-order', [PaymentController::class, 'createOrder']);
        Route::post('/capture-order/{orderId}', [PaymentController::class, 'captureOrder']);
    });

    Route::post('/products/{id}/reviews', [ReviewController::class, 'store']);

    Route::post('/products/{product}/favorite', [ProductController::class, 'toggleFavorite']);

    Route::post('/incidents', [IncidentController::class, 'store']);
    Route::get('/incidents', [IncidentController::class, 'index']);
    Route::put('/incidents/{id}', [IncidentController::class, 'update']);

    Route::get('/perfil', [App\Http\Controllers\AuthController::class, 'perfilStats']);
    Route::post('/perfil/update', [AuthController::class, 'updatePerfil']);
    Route::delete('/perfil/delete', [AuthController::class, 'deletePerfil']);
});

// ==========================================
// 3. RUTAS EXCLUSIVAS DE ADMINISTRADOR
// ==========================================
Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::prefix('admin')->group(function () {
        Route::get('/ingresos', [AdminController::class, 'ingresos']);
        Route::post('/reembolsar/{id_order}', [PaymentController::class, 'refundOrder']);
    });
});