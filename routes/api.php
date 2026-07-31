<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\PasswordController;
use App\Http\Controllers\Api\Auth\ProfileController;
use App\Http\Controllers\Api\AuthController as LoginController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [LoginController::class, 'login']);

// Public webhook endpoint for payment gateways (no auth)
Route::post('/payments/webhook', [\App\Http\Controllers\Api\Payment\WebhookController::class, 'handle']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    Route::get('/user', function (Request $request) {
        return $request->user();
    });
Route::prefix('admin')->middleware('auth:sanctum')->group(function () {
    require __DIR__.'/admin.php';
});
// et pour les routes communes
Route::prefix('')->middleware('auth:sanctum')->group(function () {
    require __DIR__.'/campaigns.php';
});

    require __DIR__.'/campaigns.php';
    require __DIR__.'/payments.php';
    require __DIR__.'/reports.php';
    require __DIR__.'/statistics.php';
    require __DIR__.'/media.php';
    require __DIR__.'/admin.php';
    require __DIR__.'/advertiser.php';
    require __DIR__.'/shared.php';
    require __DIR__.'/channels.php';
});
