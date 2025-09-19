<?php

use Illuminate\Support\Facades\Route;
use Modules\Shareholder\App\Http\Controllers\ShareholderController;
use Modules\Shareholder\App\Http\Controllers\ShareholderInviteController;
use Modules\Shareholder\App\Http\Controllers\ShareholderRegisterController;

/**
 * Route menu cha "Shareholder"
 * - Chỉ hiển thị khi user có quyền browse_shareholder_root
 * - Điều hướng mặc định tới danh sách shareholders
 */
Route::middleware(['web', 'auth', 'can:browse_shareholder_root'])
    ->get('/admin/shareholders', function () {
        return redirect()->route('shareholders.index');
    })
    ->name('shareholders.root');

// Nhóm route Shareholder UI
Route::prefix('admin/shareholders')->middleware(['web', 'auth'])->name('shareholders.')->group(function () {

    // ========= CRUD =========
    Route::get('/', [ShareholderController::class, 'index'])
        ->middleware('can:browse_shareholders')
        ->name('index');

    Route::get('/list', [ShareholderController::class, 'list'])
        ->middleware('can:browse_shareholders')
        ->name('list');

    Route::get('/create', [ShareholderController::class, 'create'])
        ->middleware('can:add_shareholders')
        ->name('create');

    Route::post('/store', [ShareholderController::class, 'store'])
        ->middleware('can:add_shareholders')
        ->name('store');

    Route::get('/{id}/edit', [ShareholderController::class, 'edit'])
        ->middleware('can:edit_shareholders')
        ->name('edit');

    Route::put('/{id}/update', [ShareholderController::class, 'update'])
        ->middleware('can:edit_shareholders')
        ->name('update');

    Route::delete('/{shareholder}/delete', [ShareholderController::class, 'destroy'])
        ->middleware('can:delete_shareholders')
        ->name('destroy');

    Route::get('/{shareholder}/detail', [ShareholderController::class, 'detail'])
        ->middleware('can:view_shareholders')
        ->name('detail');
    // ========= INVITE =========
    Route::post('/invite', [ShareholderController::class, 'invite'])
        ->name('invite');

});

// ========= REGISTER =========
Route::get('/shareholders/register/{token}', [ShareholderRegisterController::class, 'showForm'])
    ->name('shareholders.register');
Route::post('/shareholders/register', [ShareholderRegisterController::class, 'submitForm'])
    ->name('shareholders.register.submit');
