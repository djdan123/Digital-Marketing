<?php

use App\Http\Controllers\Api\Media\AdvertisementController;
use App\Http\Controllers\Api\Media\BroadcastController;
use App\Http\Controllers\Api\Media\DashboardController;
use App\Http\Controllers\Api\Media\ProfileController;
use App\Http\Controllers\Api\Media\RequestController;
use App\Http\Controllers\Api\Media\ScheduleController;
use App\Http\Controllers\Api\Media\StatisticsController;
use App\Http\Controllers\Api\Media\SubscriptionRequestController;
use Illuminate\Support\Facades\Route;

Route::get('dashboard', [DashboardController::class, 'index'])->name('media-manager.dashboard');

Route::get('requests', [RequestController::class, 'index']);
Route::get('requests/{advertisement}', [RequestController::class, 'show']);
Route::put('requests/{advertisement}/approve', [RequestController::class, 'approve']);
Route::put('requests/{advertisement}/reject', [RequestController::class, 'reject']);
Route::post('requests/{advertisement}/messages', [RequestController::class, 'message']);

Route::apiResource('advertisements', AdvertisementController::class)
    ->names('media-manager.advertisements')
    ->only(['index', 'show']);

Route::apiResource('broadcasts', BroadcastController::class)
    ->names('media-manager.broadcasts')
    ->only(['index', 'show']);

Route::put('broadcasts/{broadcast}/complete', [BroadcastController::class, 'complete']);
Route::post('broadcasts/{broadcast}/proof', [BroadcastController::class, 'uploadProof']);

Route::apiResource('subscriptions', SubscriptionRequestController::class)
    ->names('media-manager.subscriptions')
    ->only(['index', 'show']);

Route::put('subscriptions/{subscription}/approve', [SubscriptionRequestController::class, 'approve']);
Route::put('subscriptions/{subscription}/reject', [SubscriptionRequestController::class, 'reject']);

Route::apiResource('schedules', ScheduleController::class)
    ->names('media-manager.schedules')
    ->only(['index', 'show', 'update']);

Route::get('statistics', [StatisticsController::class, 'index']);
Route::get('profile', [ProfileController::class, 'index']);