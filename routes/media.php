<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\MediaController;
use Illuminate\Support\Facades\Route;

Route::apiResource('media', MediaController::class)->except(['create', 'edit']);
Route::apiResource('categories', CategoryController::class)->except(['create', 'edit']);
