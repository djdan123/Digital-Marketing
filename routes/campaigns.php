<?php

use App\Http\Controllers\Api\AdvertisementController;
use App\Http\Controllers\Api\CampaignController;
use Illuminate\Support\Facades\Route;

Route::apiResource('campaigns', CampaignController::class)->names('advertiser.campaigns')->except(['create', 'edit']);
Route::apiResource('advertisements', AdvertisementController::class)->except(['index', 'create', 'edit']);
