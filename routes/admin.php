<?php

use App\Http\Controllers\Api\Admin\AdvertisementController;
use App\Http\Controllers\Api\Admin\CampaignController;
use App\Http\Controllers\Api\Admin\MediaController;
use App\Http\Controllers\Api\Admin\RoleController;
use App\Http\Controllers\Api\Admin\SettingController;
use App\Http\Controllers\Api\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->group(function () {
    Route::get('dashboard', [\App\Http\Controllers\Api\Admin\DashboardController::class, 'index']);
    Route::apiResource('campaigns', CampaignController::class)->names('admin.campaigns')->except(['create', 'edit']);
    Route::apiResource('advertisements', AdvertisementController::class)->only(['index', 'show']);
    Route::post('advertisements/{advertisement}/approve', [AdvertisementController::class, 'approve']);
    Route::post('advertisements/{advertisement}/reject', [AdvertisementController::class, 'reject']);
    Route::apiResource('media', MediaController::class)->except(['create', 'edit']);
    Route::apiResource('roles', RoleController::class)->except(['create', 'edit']);
    Route::apiResource('settings', SettingController::class)->except(['create', 'edit']);
    Route::apiResource('users', UserController::class)->except(['create', 'edit', 'store']);
});