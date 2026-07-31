<?php

use App\Http\Controllers\Api\StatisticController;
use Illuminate\Support\Facades\Route;

Route::apiResource('statistics', StatisticController::class)->only(['index', 'show']);
