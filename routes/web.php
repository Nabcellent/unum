<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\IndicatorController;
use App\Http\Controllers\LearningAreaController;
use App\Http\Controllers\PriResultController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SecResultController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\StrandController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\SubStrandController;
use App\Http\Controllers\SummaryController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', fn() => view('welcome'));

Route::prefix('/dashboard')->middleware(['auth', 'verified'])->name('admin')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('.dashboard');

    Route::prefix('/primary')->name('.pri')->group(function() {
        Route::get('/assess/{view}', [PriResultController::class, 'getView'])->name('.assess');
        Route::get('/reports', [ReportController::class, 'indexPri'])->name('.reports');
    });
    Route::prefix('/secondary')->name('.sec')->group(function() {
        Route::get('/marks/{view}', [SecResultController::class, 'getView'])->name('.marks');
        Route::get('/reports', [ReportController::class, 'indexSec'])->name('.reports');
    });

    Route::get('/attendances', [AttendanceController::class, 'createOrEdit'])->name('.attendances');
    Route::get('/summaries/class-performance', [
        SummaryController::class,
        'classPerformance'
    ])->name('.summaries.class-performance');
    Route::get('/students', [StudentController::class, 'index'])->name('.students');
    Route::get('/classes', [GradeController::class, 'index'])->name('.classes');
    Route::get('/subjects', [SubjectController::class, 'index'])->name('.subjects');
    Route::get('/learning-areas', [LearningAreaController::class, 'index'])->name('.learning-areas');
    Route::get('/strands', [StrandController::class, 'index'])->name('.strands');
    Route::get('/sub-strands', [SubStrandController::class, 'index'])->name('.sub-strands');
    Route::get('/indicators', [IndicatorController::class, 'index'])->name('.indicators');

    Route::prefix('/settings')->name('.settings')->group(function () {
        Route::get('/', [SettingController::class, 'index']);
        Route::put('/{setting}', [SettingController::class, 'update'])->name('.update');
    });
});

require __DIR__ . '/auth.php';
