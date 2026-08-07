<?php

use App\Http\Controllers\Api\Advertiser\AdvertisementController;
use App\Http\Controllers\Api\Advertiser\CampaignController;
use App\Http\Controllers\Api\Advertiser\DashboardController;
use App\Http\Controllers\Api\Advertiser\PaymentController;
use App\Http\Controllers\Api\Advertiser\ProfileController;
use App\Http\Controllers\Api\Advertiser\ReportController;
use App\Http\Controllers\Api\Advertiser\SubscriptionController;
use App\Http\Controllers\Api\Advertiser\UploadController;
use App\Http\Controllers\Api\Advertiser\WalletController;
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

Route::apiResource('subscriptions', SubscriptionController::class)
    ->names('advertiser.subscriptions')
    ->only(['index', 'show', 'store']);

Route::get('wallet', [WalletController::class, 'index']);
Route::get('wallet/requests', [WalletController::class, 'requests']);
Route::post('wallet/requests', [WalletController::class, 'storeRequest']);

Route::get('profile', [ProfileController::class, 'index']);
Route::put('profile', [ProfileController::class, 'update']);
Route::post('uploads', [UploadController::class, 'store']);

Route::apiResource('reports', ReportController::class)
    ->names('advertiser.reports')
    ->except(['create', 'edit']);