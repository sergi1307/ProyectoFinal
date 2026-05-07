<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Importación de todos los controladores
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\IncidentController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\RefundRequestController;
use App\Http\Controllers\LogController;

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
    
    // Gestión de Usuario
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::get('/users', [AuthController::class, 'index']);
    Route::post('/users/{id}/toggle-status', [AuthController::class, 'toggleStatus']);
    Route::post('/user/subscribe', [AuthController::class, 'updateSubscription']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    // Perfil
    Route::get('/perfil', [AuthController::class, 'perfilStats']);
    Route::post('/perfil/update', [AuthController::class, 'updatePerfil']);
    Route::delete('/perfil/delete', [AuthController::class, 'deletePerfil']);

    // Productos (Acciones de Usuario)
    Route::prefix('/products')->group(function () {
        Route::get('/me', [ProductController::class, 'me']);
        Route::get('/index', [ProductController::class, 'index']); // Mantenemos index para consistencia interna
        Route::get('/favorites', [ProductController::class, 'getFavorites']);
        Route::post('/store', [ProductController::class, 'store']);
        Route::get('/destacados', [ProductController::class, 'destacados']);
        Route::get('/tendencias', [ProductController::class, 'tendencias']);
        Route::get('/{id}', [ProductController::class, 'show']);
        
        // Interacciones con productos
        Route::post('/{id}/report', [ProductController::class, 'report']);
        Route::post('/{id}/reviews', [ReviewController::class, 'store']);
        Route::post('/{product}/favorite', [ProductController::class, 'toggleFavorite']);
    });

    // Pedidos y Reembolsos (Lado Cliente)
    Route::prefix('/orders')->group(function () {
        Route::get('/', [OrderController::class, 'index']);
        Route::post('/{id_order}/refund', [RefundRequestController::class, 'store']);
    });

    // Vendedor
    Route::prefix('/seller')->group(function () {
        Route::get('/balance', [SellerController::class, 'obtenerBalances']);
    });

    // Carrito
    Route::prefix('/cart')->group(function () {
        Route::get('/', [CartController::class, 'index']);
        Route::post('/', [CartController::class, 'store']);
        Route::put('/{id}', [CartController::class, 'update']);
        Route::delete('/{id}', [CartController::class, 'destroy']);
    });

    // Pasarela de Pago
    Route::prefix('/paypal')->group(function () {
        Route::post('/create-order', [PaymentController::class, 'createOrder']);
        Route::post('/capture-order/{orderId}', [PaymentController::class, 'captureOrder']);
    });

    // Incidencias (Soporte)
    Route::prefix('/incidents')->group(function () {
        Route::get('/', [IncidentController::class, 'index']);
        Route::post('/', [IncidentController::class, 'store']);
        Route::put('/{id}', [IncidentController::class, 'update']);
    });
});

// ==========================================
// 3. RUTAS EXCLUSIVAS DE ADMINISTRADOR
// ==========================================
Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::prefix('admin')->group(function () {
        // Dashboard Financiero
        Route::get('/ingresos', [AdminController::class, 'ingresos']);
        
        // Gestión de Reembolsos
        Route::get('/refund-requests', [RefundRequestController::class, 'index']);
        Route::post('/refunds/{id_order}/approve', [PaymentController::class, 'refundOrder']);
        
        // Moderación de Productos y Reportes
        Route::get('/reports', [AdminController::class, 'getPendingReports']);
        Route::get('/reports/history', [AdminController::class, 'getHistoryReports']);
        Route::post('/products/{id}/approve', [AdminController::class, 'approveProduct']);
        Route::post('/products/{id}/reject', [AdminController::class, 'rejectProduct']);
        
        // Sistema de Auditoría
        Route::get('/logs', [LogController::class, 'index']);

        // Modo Mantenimiento
        Route::get('/maintenance-status', [AdminController::class, 'getMaintenanceStatus']);
        Route::post('/toggle-maintenance', [AdminController::class, 'toggleMaintenance']);
    });
});