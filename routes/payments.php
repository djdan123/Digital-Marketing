<?php

use App\Http\Controllers\Api\PaymentController;
use Illuminate\Support\Facades\Route;

Route::apiResource('payments', PaymentController::class)->only(['index', 'show', 'store']);
