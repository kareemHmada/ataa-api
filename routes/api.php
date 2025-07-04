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




/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::prefix('mobile')->group(function () {
    Route::get('/donations', [DonationController::class, 'index']);
    Route::post('/donations', [DonationController::class, 'store']);
});

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login',    [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {


        Route::get('/profile', [AuthController::class, 'profile']);
        Route::post('/logout', [AuthController::class, 'logout']);

        Route::get('/donations',  [DonationController::class, 'index']);
        Route::post('/donations', [DonationController::class, 'store']);

        Route::get('/dashboard', [DashboardController::class, 'index']);

        Route::get('/conversations', [MessageController::class, 'conversations']);

        Route::get('/donation-requests', [DonationRequestController::class, 'index']);
        Route::post('/donation-requests', [DonationRequestController::class, 'store']);
        Route::put('/donation-requests/{id}/status', [DonationRequestController::class, 'updateStatus']);

        Route::get('/conversations', [MessageController::class, 'conversations']);
        Route::get('/conversations/{id}/messages', [MessageController::class, 'messages']);
        Route::post('/conversations/{id}/messages', [MessageController::class, 'send']);
    });
});

Route::post('/contact', [ContactController::class, 'store']);
Route::get('/home-stats', [HomeStatsController::class, 'index']);
Route::middleware('auth:sanctum')->post('/fcm/token', [NotificationController::class, 'updateToken']);

 