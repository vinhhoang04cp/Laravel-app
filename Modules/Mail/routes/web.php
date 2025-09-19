<?php

use Illuminate\Support\Facades\Route;
use Modules\Mail\App\Http\Controllers\Web\MailLogController;
use Modules\Mail\App\Http\Controllers\Web\MailTemplateWebController;
use Modules\Mail\App\Http\Controllers\Web\MailConfigWebController;
use Modules\Mail\App\Http\Controllers\Web\MailPreviewWebController;

/**
 * Route menu cha "Mail"
 * - Chỉ hiện với user có quyền browse_mail_root
 * - Điều hướng mặc định vào danh sách templates
 */
Route::middleware(['web', 'auth', 'can:browse_mail_root'])
    ->get('/admin/mail', function () {
        return redirect()->route('mail.ui.templates.index');
    })
    ->name('mail.root');

// Nhóm route Mail UI
Route::prefix('admin/mail')->middleware(['web', 'auth'])->name('mail.ui.')->group(function () {

    // ========= TEMPLATES =========
    Route::get('/templates', [MailTemplateWebController::class, 'index'])
        ->middleware('can:browse_mail_templates')
        ->name('templates.index');

    Route::get('/templates/create', [MailTemplateWebController::class, 'create'])
        ->middleware('can:add_mail_templates')
        ->name('templates.create');

    Route::post('/templates', [MailTemplateWebController::class, 'store'])
        ->middleware('can:add_mail_templates')
        ->name('templates.store');

    Route::get('/templates/{id}', [MailTemplateWebController::class, 'show'])
        ->middleware('can:read_mail_templates')
        ->name('templates.show');

    Route::get('/templates/{id}/edit', [MailTemplateWebController::class, 'edit'])
        ->middleware('can:edit_mail_templates')
        ->name('templates.edit');

    Route::put('/templates/{id}', [MailTemplateWebController::class, 'update'])
        ->middleware('can:edit_mail_templates')
        ->name('templates.update');

    Route::delete('/templates/{id}', [MailTemplateWebController::class, 'destroy'])
        ->middleware('can:delete_mail_templates')
        ->name('templates.destroy');

    // Preview JSON endpoint cho UI
    Route::post('/preview', [MailPreviewWebController::class, 'render'])
        ->middleware('can:browse_mail_templates')
        ->name('preview');

    // ========= CONFIGS =========
    Route::get('/configs', [MailConfigWebController::class, 'index'])
        ->middleware('can:browse_mail_configs')
        ->name('configs.index');

    Route::get('/configs/create', [MailConfigWebController::class, 'create'])
        ->middleware('can:add_mail_configs')
        ->name('configs.create');

    Route::post('/configs', [MailConfigWebController::class, 'store'])
        ->middleware('can:add_mail_configs')
        ->name('configs.store');

    Route::get('/configs/{id}/edit', [MailConfigWebController::class, 'edit'])
        ->middleware('can:edit_mail_configs')
        ->name('configs.edit');

    Route::put('/configs/{id}', [MailConfigWebController::class, 'update'])
        ->middleware('can:edit_mail_configs')
        ->name('configs.update');

    Route::delete('/configs/{id}', [MailConfigWebController::class, 'destroy'])
        ->middleware('can:delete_mail_configs')
        ->name('configs.destroy');

    Route::post('/configs/{id}/activate', [MailConfigWebController::class, 'activate'])
        ->middleware('can:edit_mail_configs')
        ->name('configs.activate');

    // Gửi thử email bằng config cụ thể
    Route::get('/configs/{id}/send-test', [MailConfigWebController::class, 'sendTestForm'])
        ->middleware('can:send_test_mail')
        ->name('send.test.form');

    Route::post('/configs/{id}/send-test', [MailConfigWebController::class, 'sendTest'])
        ->middleware('can:send_test_mail')
        ->name('send.test');

    // ========= LOGS =========
    Route::get('logs', [MailLogController::class, 'index'])
        ->middleware('can:browse_mail_logs')
        ->name('logs.index');

    Route::get('logs/{id}', [MailLogController::class, 'show'])
        ->middleware('can:read_mail_logs')
        ->name('logs.show');
});
