<?php

use App\Http\Controllers\Api\AlertController;
use App\Http\Controllers\Api\IntrusionController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/alerts', [AlertController::class, 'index']);
    Route::get('/alerts/unread-count', [AlertController::class, 'unreadCount']);
    Route::get('/alerts/{alert}', [AlertController::class, 'show']);
    Route::post('/alerts/{alert}/mark-read', [AlertController::class, 'markAsRead']);

    Route::get('/intrusions', [IntrusionController::class, 'index']);
    Route::get('/intrusions/stats', [IntrusionController::class, 'stats']);
    Route::get('/intrusions/{event}', [IntrusionController::class, 'show']);
});
