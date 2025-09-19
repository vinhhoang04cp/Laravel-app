<?php

use Illuminate\Support\Facades\Route;
use Modules\Congress\App\Http\Controllers\BallotController;
use Modules\Congress\App\Http\Controllers\CongressController;
use Modules\Congress\App\Http\Controllers\QueueController;

/**
 * Route menu cha "Congress"
 * - Chỉ hiển thị khi user có quyền browse_congress_root
 * - Điều hướng mặc định tới danh sách congress
 */
Route::middleware(['web', 'auth', 'can:browse_congress_root'])
    ->get('/admin/congresses', function () {
        return redirect()->route('congresses.index');
    })
    ->name('congress.root');

// Nhóm route Congress UI
Route::prefix('admin/congresses')->middleware(['web', 'auth'])->name('congresses.')->group(function () {

    // ========= CRUD =========
    Route::get('/', [CongressController::class, 'index'])
        ->middleware('can:browse_congresses')
        ->name('index');

    Route::get('/list', [CongressController::class, 'list'])
        ->middleware('can:browse_congresses')
        ->name('list');

    Route::get('/create', [CongressController::class, 'create'])
        ->middleware('can:add_congresses')
        ->name('create');

    Route::post('/store', [CongressController::class, 'store'])
        ->middleware('can:add_congresses')
        ->name('store');

    Route::get('/{congress}/edit', [CongressController::class, 'edit'])
        ->middleware('can:edit_congresses')
        ->name('edit');

    Route::put('/{congress}/update', [CongressController::class, 'update'])
        ->middleware('can:edit_congresses')
        ->name('update');

    Route::delete('/{congress}/delete', [CongressController::class, 'destroy'])
        ->middleware('can:delete_congresses')
        ->name('destroy');

    // ========= IMPORT =========
    Route::post('/import', [CongressController::class, 'import'])
        ->middleware('can:import_congresses')
        ->name('import');

    // ========= SHAREHOLDERS =========
    Route::get('/{congress}/shareholders', [CongressController::class, 'shareholders'])
        ->middleware('can:browse_congress_shareholders')
        ->name('shareholders');

    Route::delete('/{congress}/shareholders/remove', [CongressController::class, 'removeShareholder'])
        ->middleware('can:delete_congress_shareholders')
        ->name('shareholders.remove');

    Route::get('/{congress}/shareholders/list', [CongressController::class, 'shareholderList'])
        ->middleware('can:browse_congress_shareholders')
        ->name('shareholders.list');

    Route::get('/shareholders/ballots/export', [BallotController::class, 'exportAll'])
        ->name('shareholders.ballots.export');

    Route::get('/print', [BallotController::class, 'exportById']);

    // ========= QUEUE =========
    Route::get('/queue', [QueueController::class, 'index'])
        ->middleware('can:browse_shareholders_queue')
        ->name('queue.index');
});
Route::get('shareholders/test/{congress_id}', [BallotController::class, 'test']);
