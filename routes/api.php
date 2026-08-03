<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Api\Auth\VerifyEmailController;
use App\Http\Controllers\Api\Payment\WebhookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Routes publiques
Route::post('/register', [RegisteredUserController::class, 'store']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password', [PasswordResetLinkController::class, 'store']);
Route::post('/reset-password', [NewPasswordController::class, 'store']);
Route::post('/payments/webhook', [WebhookController::class, 'handle']);

// Routes protégées par authentification
Route::middleware(['auth:sanctum'])->group(function () {
    // Utilisateur courant
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Groupe Admin (préfixe /admin)
    Route::prefix('admin')->group(function () {
        require __DIR__ . '/admin.php';
    });

    // Groupe Annonceur (préfixe /advertiser)
    Route::prefix('advertiser')->group(function () {
        require __DIR__ . '/advertiser.php';
    });

    // Groupe Responsable Média (préfixe /media-manager)
    Route::prefix('media-manager')->group(function () {
        require __DIR__ . '/channels.php';
    });

    // Routes partagées (lecture) (préfixe /shared)
    Route::prefix('shared')->group(function () {
        require __DIR__ . '/shared.php';
    });
});