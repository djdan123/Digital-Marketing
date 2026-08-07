<?php

use App\Http\Controllers\Admin\CampaignController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->file(public_path('index.html'));
});

Route::get('/admin', function () {
    return response()->file(public_path('admin/index.html'));
});

Route::get('/admin/login', function () {
    return response()->file(public_path('admin/login.html'));
});

Route::get('/admin/login.html', function () {
    return response()->file(public_path('admin/login.html'));
});

Route::get('/admin/index.html', function () {
    return response()->file(public_path('admin/index.html'));
});

Route::prefix('admin')->group(function () {
    Route::get('/{any?}', function (Request $request, ?string $any = null) {
        $relativePath = $any ?? '';
        $basePath = public_path('admin');
        $candidate = $relativePath === '' ? $basePath . DIRECTORY_SEPARATOR . 'index.html' : $basePath . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $relativePath);

        if (is_file($candidate)) {
            return response()->file($candidate);
        }

        if (is_dir($candidate)) {
            $indexFile = $candidate . DIRECTORY_SEPARATOR . 'index.html';
            if (is_file($indexFile)) {
                return response()->file($indexFile);
            }
        }

        if (!str_contains($candidate, '.')) {
            $htmlFile = $candidate . '.html';
            if (is_file($htmlFile)) {
                return response()->file($htmlFile);
            }
        }

        return response()->file(public_path('admin/index.html'));
    })->where('any', '.*');
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
