<?php

use App\Http\Controllers\Admin\CampaignController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('media', MediaController::class)->names('web.media')->except(['show']);
    Route::resource('campaigns', CampaignController::class)->names('web.campaigns')->except(['show']);
    Route::resource('users', UserController::class)->names('web.users')->except(['show', 'create', 'store']);
    Route::post('campaigns/{campaign}/approve', [CampaignController::class, 'approve'])->name('web.campaigns.approve');
    Route::post('campaigns/{campaign}/reject', [CampaignController::class, 'reject'])->name('web.campaigns.reject');
});

require __DIR__.'/auth.php';
