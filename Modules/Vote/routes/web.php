<?php

use Illuminate\Support\Facades\Route;
use Modules\Vote\App\Http\Controllers\VoteSessionController;
use Modules\Vote\App\Http\Controllers\VoteController;
use Modules\Vote\App\Http\Controllers\PublicVoteController;

/**
 * Route menu cha "Vote"
 * - Chỉ hiện với user có quyền browse_vote_root
 * - Điều hướng mặc định vào danh sách vote sessions
 */
Route::middleware(['web', 'auth', 'can:browse_vote_root'])
    ->get('/admin/vote', function () {
        return redirect()->route('vote.ui.sessions.index');
    })
    ->name('vote.root');

// Nhóm route quản trị Vote UI (dành cho admin qua Voyager)
Route::prefix('admin/vote')->middleware(['web', 'auth'])->name('vote.ui.')->group(function () {

    // ========= VOTE SESSIONS =========
    Route::get('/sessions', [VoteSessionController::class, 'index'])
        ->middleware('can:browse_vote_sessions')
        ->name('sessions.index');

    Route::get('/sessions/list', [VoteSessionController::class, 'listData'])
//        ->middleware('can:get_vote_sessions')
        ->name('sessions.list');

    Route::get('/sessions/create', [VoteSessionController::class, 'create'])
        ->middleware('can:add_vote_sessions')
        ->name('sessions.create');

    Route::post('/sessions', [VoteSessionController::class, 'store'])
        ->middleware('can:add_vote_sessions')
        ->name('sessions.store');

    Route::get('/sessions/{id}', [VoteSessionController::class, 'show'])
        ->middleware('can:read_vote_sessions')
        ->name('sessions.show');

    Route::get('/sessions/{id}/edit', [VoteSessionController::class, 'edit'])
        ->middleware('can:edit_vote_sessions')
        ->name('sessions.edit');

    Route::put('/sessions/{id}', [VoteSessionController::class, 'update'])
        ->middleware('can:edit_vote_sessions')
        ->name('sessions.update');

    Route::delete('/sessions/{id}', [VoteSessionController::class, 'destroy'])
        ->middleware('can:delete_vote_sessions')
        ->name('sessions.destroy');

    // ========= VOTES (admin chỉ xem, không tạo mới) =========
    Route::get('/votes', [VoteController::class, 'index'])
        ->middleware('can:browse_votes')
        ->name('votes.index');

    Route::get('/votes/{id}', [VoteController::class, 'show'])
        ->middleware('can:read_votes')
        ->name('votes.show');

    Route::delete('/votes/{id}', [VoteController::class, 'destroy'])
        ->middleware('can:delete_votes')
        ->name('votes.destroy');

    // ========= VOTES (admin nhập vote thủ công) =========
    Route::post('/votes/submit', [VoteController::class, 'submit'])
        ->name('votes.submit');
});


// ================== PUBLIC VOTE (người dùng từ email) ==================
// Không cần auth, chỉ middleware web
Route::prefix('vote')->name('vote.public.')->group(function () {
    Route::get('/{id}', [PublicVoteController::class, 'showForm'])
        ->whereNumber('id')
        ->name('form');
    Route::post('/{id}', [PublicVoteController::class, 'submitVote'])
        ->name('submit');
});
