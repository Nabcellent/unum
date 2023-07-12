<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\SummaryController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('/grades')->group(function() {
    Route::get('/', [GradeController::class, 'getGrades']);

    Route::prefix('/{grade}')->group(function() {
        Route::get('/subjects', [GradeController::class, 'subjects']);
        Route::get('/results', [GradeController::class, 'results']);
        Route::get('/students', [GradeController::class, 'results']);

        Route::get('/attendances', [AttendanceController::class, 'index']);
        Route::put('/attendances', [AttendanceController::class, 'upsert']);
    });
});

Route::prefix('/subjects')->group(function() {
    Route::get('/', [SubjectController::class, 'getSubjects']);
    Route::post('/', [SubjectController::class, 'store']);
    Route::put('/{subject}', [SubjectController::class, 'update']);
    Route::delete('/{subject}', [SubjectController::class, 'destroy']);
});

Route::prefix('/students')->group(function() {
    Route::get('/{gradeId}', [StudentController::class, 'getByGradeId']);
    Route::get('/{student}/results', [StudentController::class, 'results']);
});

Route::prefix('/results')->group(function() {
    Route::post('/subject', [ResultController::class, 'storeSubject']);
    Route::post('/students/{student}', [ResultController::class, 'storeStudent']);
});

Route::prefix('/reports/exams/{exam}/grades/{grade}')->group(function() {
    Route::get('/preview', [ReportController::class, 'preview']);
    Route::post('/', [ReportController::class, 'store']);
});

Route::prefix('/summaries/exams/{exam}')->group(function() {
    Route::get('/preview', [SummaryController::class, 'preview']);
    Route::post('/', [SummaryController::class, 'store']);
});
