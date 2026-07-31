<?php

use App\Http\Controllers\Api\Admin\AdvertisementController;
use App\Http\Controllers\Api\Admin\CampaignController;
use App\Http\Controllers\Api\Admin\DashboardController;
use App\Http\Controllers\Api\Admin\MediaController;
use App\Http\Controllers\Api\Admin\RoleController;
use App\Http\Controllers\Api\Admin\SettingController;
use App\Http\Controllers\Api\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::get('dashboard', [DashboardController::class, 'index']);

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

Route::apiResource('roles', RoleController::class)
    ->names('admin.roles')
    ->except(['create', 'edit']);

Route::apiResource('settings', SettingController::class)
    ->names('admin.settings')
    ->except(['create', 'edit']);

Route::apiResource('users', UserController::class)
    ->names('admin.users')
    ->except(['create', 'edit', 'store']);