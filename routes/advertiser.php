<?php

use App\Http\Controllers\Api\Advertiser\AdvertisementController;
use App\Http\Controllers\Api\Advertiser\CampaignController;
use App\Http\Controllers\Api\Advertiser\DashboardController;
use App\Http\Controllers\Api\Advertiser\PaymentController;
use App\Http\Controllers\Api\Advertiser\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('dashboard', [DashboardController::class, 'index']);

Route::apiResource('campaigns', CampaignController::class)
    ->names('advertiser.campaigns')
    ->except(['create', 'edit']);

Route::apiResource('advertisements', AdvertisementController::class)
    ->names('advertiser.advertisements')
    ->except(['create', 'edit']);

Route::apiResource('payments', PaymentController::class)
    ->names('advertiser.payments')
    ->only(['index', 'show']);

Route::apiResource('reports', ReportController::class)
    ->names('advertiser.reports')
    ->except(['create', 'edit']);