<?php

use Illuminate\Support\Facades\Route;
use Modules\Mail\App\Http\Controllers\MailTemplateController;
use Modules\Mail\App\Http\Controllers\MailConfigController;
use Modules\Mail\App\Http\Controllers\MailSendController;
use Modules\Mail\App\Http\Controllers\MailPreviewController;

Route::prefix('api/v1/mail')->group(function () {

    // TODO: ->middleware(['auth:sanctum']) // và permission theo RACL của bạn

    Route::get('/templates', [MailTemplateController::class, 'index']);
    Route::get('/templates/{id}', [MailTemplateController::class, 'show']);
    Route::post('/templates', [MailTemplateController::class, 'store']);
    Route::put('/templates/{id}', [MailTemplateController::class, 'update']);
    Route::delete('/templates/{id}', [MailTemplateController::class, 'destroy']);

    Route::get('/configs', [MailConfigController::class, 'index']);
    Route::get('/configs/{id}', [MailConfigController::class, 'show']);
    Route::post('/configs', [MailConfigController::class, 'store']);
    Route::put('/configs/{id}', [MailConfigController::class, 'update']);
    Route::delete('/configs/{id}', [MailConfigController::class, 'destroy']);
    Route::post('/configs/{id}/activate', [MailConfigController::class, 'activate']);

    Route::post('/send-test', [MailSendController::class, 'sendTest']);
    Route::post('/preview', [MailPreviewController::class, 'render']);
});
