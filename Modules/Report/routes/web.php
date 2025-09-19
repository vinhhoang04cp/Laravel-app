<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Modules\Report\App\Http\Controllers\AttendanceController;
use Modules\Report\App\Http\Controllers\CapitalContributionController;
use Modules\Report\App\Http\Controllers\ReportController;
use Modules\Report\App\Http\Controllers\VotingController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::prefix('admin/reports')->middleware(['web', 'auth'])->name('reports.')->group(function () {
    Route::get('/', function () {
        return redirect()->route('reports.capital_contributions.index');
    })->middleware(['web', 'auth', 'can:browse_report_root'])
        ->name('report.root');

    /**
     * Báo cáo quá trình góp vốn
     */
    Route::prefix('capital-contributions')->name('capital_contributions.')->group(function () {
        Route::get('/', [CapitalContributionController::class, 'index'])
            ->middleware('can:browse_capital_contribution_reports')
            ->name('index');

        Route::get('/export', [CapitalContributionController::class, 'index'])
            ->middleware('can:export_capital_contribution_reports')
            ->name('export');
    });

    /**
     * Báo cáo biểu quyết
     */
    Route::prefix('voting')->name('voting.')->group(function () {
        Route::get('/', [VotingController::class, 'index'])
            ->middleware('can:browse_voting_reports')
            ->name('index');

        Route::get('/load-report', [VotingController::class, 'loadReport'])
            ->middleware('can:browse_voting_reports')
            ->name('load');

        Route::get('/export', [VotingController::class, 'export'])
            ->middleware('can:export_voting_reports')
            ->name('export');
    });

    /**
     * Báo cáo tham dự
     */
    Route::prefix('attendances')->name('attendances.')->group(function () {
        Route::get('/', [AttendanceController::class, 'index'])
            ->middleware('can:browse_attendance_reports')
            ->name('index');

        Route::get('/load-report', [AttendanceController::class, 'loadReport'])
            ->middleware('can:browse_attendance_reports')
            ->name('load');

        Route::get('/export', [AttendanceController::class, 'export'])
            ->middleware('can:export_attendance_reports')
            ->name('export');
    });
});
