<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\DonationController;
use App\Http\Controllers\Api\DonationRequestController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\HomeStatsController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\AdminController;

// 🔐 AUTH ROUTES
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/profile', [AuthController::class, 'profile']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });
});

// 🔐 MAIN USER ROUTES
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);

    Route::get('/donations', [DonationController::class, 'index']);
    Route::post('/donations', [DonationController::class, 'store']);
    Route::get('/donations/{id}', [DonationController::class, 'show']);
    Route::put('/donations/{id}/change-status', [DonationController::class, 'changeStatus']);
    Route::delete('/donations/{id}', [DonationController::class, 'destroy']);

    Route::get('/donation-requests', [DonationRequestController::class, 'index']);
    Route::post('/donation-requests', [DonationRequestController::class, 'store']);
    Route::get('/donation-requests/{id}', [DonationRequestController::class, 'show']);
    Route::put('/donation-requests/{id}/status', [DonationRequestController::class, 'updateStatus']);
    Route::delete('/donation-requests/{id}', [DonationRequestController::class, 'destroy']); // ✅ مضافة

    Route::post('/notifications/token', [NotificationController::class, 'updateToken']);
    Route::post('/notifications/broadcast', [NotificationController::class, 'broadcastGlobal']);
});

Route::get('/home-stats', [HomeStatsController::class, 'index']);
Route::post('/contact', [ContactController::class, 'store']);

Route::prefix('mobile')->group(function () {
    Route::get('/donations', [DonationController::class, 'index']);
    Route::post('/donations', [DonationController::class, 'store']);
});

Route::middleware(['auth:sanctum'])->prefix('admin')->group(function () {
    Route::get('/users', [AdminController::class, 'getAllUsers']);
    Route::post('/users/{id}/verify', [AdminController::class, 'verifyUser']);
    Route::delete('/users/{id}', [AdminController::class, 'deleteUser']);

    Route::get('/donations', [AdminController::class, 'getAllDonations']);
    Route::post('/donations/{id}/change-status', [AdminController::class, 'changeDonationStatus']);
    Route::post('/donations/{id}/confirm', [AdminController::class, 'confirmDonation']);
    Route::delete('/donations/{id}', [AdminController::class, 'deleteDonation']);
});
