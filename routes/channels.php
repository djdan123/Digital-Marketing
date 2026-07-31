<?php

use App\Http\Controllers\Api\Media\AdvertisementController;
use App\Http\Controllers\Api\Media\BroadcastController;
use App\Http\Controllers\Api\Media\DashboardController;
use App\Http\Controllers\Api\Media\ScheduleController;
use Illuminate\Support\Facades\Route;

Route::prefix('media-manager')->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index']);
    Route::apiResource('advertisements', AdvertisementController::class)->only(['index', 'show']);
    Route::apiResource('broadcasts', BroadcastController::class)->only(['index', 'show']);
    Route::apiResource('schedules', ScheduleController::class)->only(['index', 'show']);
});
