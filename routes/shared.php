<?php

use App\Http\Controllers\Api\Shared\CategoryController;
use App\Http\Controllers\Api\Shared\MediaController;
use App\Http\Controllers\Api\Shared\ReportController;
use App\Http\Controllers\Api\Shared\StatisticController;
use Illuminate\Support\Facades\Route;

Route::prefix('shared')->group(function () {
    Route::get('categories', [CategoryController::class, 'index']);
    Route::get('categories/{category}', [CategoryController::class, 'show']);

    Route::get('media', [MediaController::class, 'index']);
    Route::get('media/{media}', [MediaController::class, 'show']);

    Route::get('reports', [ReportController::class, 'index']);
    Route::get('reports/{report}', [ReportController::class, 'show']);

    Route::get('statistics', [StatisticController::class, 'index']);
    Route::get('statistics/{statistic}', [StatisticController::class, 'show']);
});