<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\NotificationTemplateController;
use App\Http\Controllers\Api\V1\NotificationController;
use Illuminate\Support\Facades\Redis;

Route::prefix('v1')->group(function () {

    Route::get('/user', function (Request $request) {
        return $request->user();
    })->middleware('auth:sanctum');

    Route::apiResource('notification-templates', NotificationTemplateController::class);

    Route::get('notifications', [NotificationController::class, 'index']);
    Route::get('notifications/{id}', [NotificationController::class, 'show']);
    Route::post('notifications', [NotificationController::class, 'store'])
        ->middleware('throttle:10,60');
    Route::put('notifications/{id}', [NotificationController::class, 'update']);
    Route::delete('notifications/{id}', [NotificationController::class, 'destroy']);

    Route::get('users/{userId}/notifications', [NotificationController::class, 'recent']);
    Route::get('notifications-summary/{tenantId?}', [NotificationController::class, 'summary']);
});
