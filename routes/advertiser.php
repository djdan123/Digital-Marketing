<?php

use App\Http\Controllers\Api\Advertiser\AdvertisementController;
use App\Http\Controllers\Api\Advertiser\CampaignController;
use App\Http\Controllers\Api\Advertiser\DashboardController;
use App\Http\Controllers\Api\Advertiser\PaymentController;
use App\Http\Controllers\Api\Advertiser\ReportController;
use Illuminate\Support\Facades\Route;

Route::prefix('advertiser')->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index']);
    Route::apiResource('campaigns', CampaignController::class)->except(['create', 'edit']);
    Route::apiResource('advertisements', AdvertisementController::class)->except(['create', 'edit']);
    Route::apiResource('payments', PaymentController::class)->only(['index', 'show']);
    Route::apiResource('reports', ReportController::class)->except(['create', 'edit']);
});