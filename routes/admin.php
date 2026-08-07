<?php

use App\Http\Controllers\Api\Admin\AdvertisementController;
use App\Http\Controllers\Api\Admin\AdvertiserController;
use App\Http\Controllers\Api\Admin\CampaignController;
use App\Http\Controllers\Api\Admin\CategoryController;      // ✅ nouveau
use App\Http\Controllers\Api\Admin\NotificationController;  // ✅ nouveau
use App\Http\Controllers\Api\Admin\PricingController;       // ✅ nouveau (optionnel)
use App\Http\Controllers\Api\Admin\CommissionController;
use App\Http\Controllers\Api\Admin\DashboardController;
use App\Http\Controllers\Api\Admin\MediaController;
use App\Http\Controllers\Api\Admin\RoleController;
use App\Http\Controllers\Api\Admin\SettingController;
use App\Http\Controllers\Api\Admin\UserController;
use App\Http\Controllers\Api\Admin\WalletRequestController;
use Illuminate\Support\Facades\Route;

Route::get('dashboard', [DashboardController::class, 'index']);
Route::get('commissions', [CommissionController::class, 'index']);

Route::get('advertisers', [AdvertiserController::class, 'index']);
Route::get('advertisers/{advertiser}', [AdvertiserController::class, 'show']);

Route::apiResource('campaigns', CampaignController::class)
    ->names('admin.campaigns')
    ->except(['create', 'edit']);

Route::apiResource('advertisements', AdvertisementController::class)
    ->names('admin.advertisements')
    ->only(['index', 'show']);

Route::post('advertisements/{advertisement}/approve', [AdvertisementController::class, 'approve'])
    ->name('admin.advertisements.approve');
Route::post('advertisements/{advertisement}/reject', [AdvertisementController::class, 'reject'])
    ->name('admin.advertisements.reject');

Route::apiResource('media', MediaController::class)
    ->names('admin.media')
    ->except(['create', 'edit']);
    // ========== NOUVEAU : Catégories ==========
Route::apiResource('categories', CategoryController::class)
    ->names('admin.categories')
    ->except(['create', 'edit']);

// ========== NOUVEAU : Notifications ==========
Route::apiResource('notifications', NotificationController::class)
    ->names('admin.notifications')
    ->except(['create', 'edit']);

// ========== NOUVEAU : Tarifs (optionnel) ==========
Route::get('pricing', [PricingController::class, 'index']);
Route::post('pricing', [PricingController::class, 'store']);
Route::put('pricing', [PricingController::class, 'update']);

Route::apiResource('roles', RoleController::class)
    ->names('admin.roles')
    ->except(['create', 'edit']);

Route::apiResource('settings', SettingController::class)
    ->names('admin.settings')
    ->except(['create', 'edit']);

Route::apiResource('users', UserController::class)
    ->names('admin.users')
    ->except(['create', 'edit', 'store']);

Route::get('wallet/requests', [WalletRequestController::class, 'index']);
Route::post('wallet/requests/{report_id}/approve', [WalletRequestController::class, 'approve']);
Route::post('wallet/requests/{report_id}/reject', [WalletRequestController::class, 'reject']);