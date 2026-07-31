<?php

use App\Http\Controllers\Api\Shared\CategoryController;
use App\Http\Controllers\Api\Shared\MediaController;
use App\Http\Controllers\Api\Shared\ReportController;
use App\Http\Controllers\Api\Shared\StatisticController;
use Illuminate\Support\Facades\Route;

Route::get('categories', [CategoryController::class, 'index'])->name('shared.categories.index');
Route::get('categories/{category}', [CategoryController::class, 'show'])->name('shared.categories.show');

Route::get('media', [MediaController::class, 'index'])->name('shared.media.index');
Route::get('media/{media}', [MediaController::class, 'show'])->name('shared.media.show');

Route::get('reports', [ReportController::class, 'index'])->name('shared.reports.index');
Route::get('reports/{report}', [ReportController::class, 'show'])->name('shared.reports.show');

Route::get('statistics', [StatisticController::class, 'index'])->name('shared.statistics.index');
Route::get('statistics/{statistic}', [StatisticController::class, 'show'])->name('shared.statistics.show');