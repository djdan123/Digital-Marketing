<?php

use App\Http\Controllers\Api\Media\AdvertisementController;
use App\Http\Controllers\Api\Media\BroadcastController;
use App\Http\Controllers\Api\Media\DashboardController;
use App\Http\Controllers\Api\Media\ScheduleController;
use Illuminate\Support\Facades\Route;

Route::get('dashboard', [DashboardController::class, 'index'])->name('media-manager.dashboard');

Route::apiResource('advertisements', AdvertisementController::class)
    ->names('media-manager.advertisements')
    ->only(['index', 'show']);

Route::apiResource('broadcasts', BroadcastController::class)
    ->names('media-manager.broadcasts')
    ->only(['index', 'show']);

Route::apiResource('schedules', ScheduleController::class)
    ->names('media-manager.schedules')
    ->only(['index', 'show']);