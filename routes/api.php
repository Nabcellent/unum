<?php

use App\Http\Controllers\GradeController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\StudentController;
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

Route::prefix('/grades/{grade}')->group(function() {
    Route::get('/subjects', [GradeController::class, 'subjects']);
    Route::get('/results', [GradeController::class, 'results']);
    Route::get('/students', [GradeController::class, 'results']);
});

Route::get('/students/{student}/results', [StudentController::class, 'results']);

Route::prefix('/results')->group(function() {
    Route::post('/subject', [ResultController::class, 'storeSubject']);
    Route::post('/students/{student}', [ResultController::class, 'storeStudent']);
});

Route::prefix('/reports/exams/{exam}/grades/{grade}')->group(function() {
    Route::get('/preview', [ReportController::class, 'preview']);
});
