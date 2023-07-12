<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
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

    Route::prefix('/marks')->name('.assessment')->group(function () {
        Route::get('/student', [ResultController::class, 'createOrEditStudent'])->name('.student');
        Route::get('/subject', [ResultController::class, 'createOrEditSubject'])->name('.subject');
    });

    Route::prefix('/attendances')->name('.attendances')->group(function () {
        Route::get('/', [AttendanceController::class, 'createOrEdit']);
    });

    Route::prefix('/reports')->name('.reports')->group(function () {
        Route::get('/', [ReportController::class, 'index']);
    });

    Route::prefix('/summaries')->name('.summaries')->group(function () {
        Route::get('/stream-performance', [SummaryController::class, 'streamPerformance'])->name('.stream-performance');
    });

    Route::prefix('/students')->name('.students')->group(function () {
        Route::get('/', [StudentController::class, 'index']);
    });

    Route::prefix('/classes')->name('.classes')->group(function () {
        Route::get('/', [GradeController::class, 'index']);
    });

    Route::prefix('/subjects')->name('.subjects')->group(function () {
        Route::get('/', [SubjectController::class, 'index']);
    });

    Route::prefix('/settings')->name('.settings')->group(function () {
        Route::get('/', [SettingController::class, 'index']);
        Route::put('/{setting}', [SettingController::class, 'update'])->name('.update');
    });
});

require __DIR__ . '/auth.php';
