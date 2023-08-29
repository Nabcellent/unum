<?php

use App\Http\Controllers\Api\GradeController;
use App\Http\Controllers\Api\IndicatorController;
use App\Http\Controllers\Api\LearningAreaController;
use App\Http\Controllers\Api\StrandController;
use App\Http\Controllers\Api\SubStrandController;
use App\Http\Controllers\AttendanceController;
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

Route::prefix('/grades')->group(function () {
    Route::get('/', [GradeController::class, 'getGrades']);

    Route::prefix('/{grade}')->group(function () {
        Route::get('/subjects', [GradeController::class, 'getSubjects']);
        Route::put('/subjects', [GradeController::class, 'syncSubjects']);
        Route::get('/learning-areas', [GradeController::class, 'getLearningAreas']);
        Route::put('/learning-areas', [GradeController::class, 'syncLearningAreas']);


        Route::get('/results', [GradeController::class, 'getResults']);
        Route::get('/students', [GradeController::class, 'getStudents']);

        Route::get('/attendances', [AttendanceController::class, 'index']);
        Route::put('/attendances', [AttendanceController::class, 'upsert']);
    });
});

Route::prefix('/subjects')->group(function () {
    Route::get('/', [SubjectController::class, 'getSubjects']);
    Route::post('/', [SubjectController::class, 'store']);
    Route::put('/{subject}', [SubjectController::class, 'update']);
    Route::delete('/{subject}', [SubjectController::class, 'destroy']);
});

Route::prefix('/learning-areas')->group(function () {
    Route::get('/', [LearningAreaController::class, 'getLearningAreas']);
    Route::get('/{learningArea}/strands', [LearningAreaController::class, 'getStrands']);
    Route::post('/', [LearningAreaController::class, 'store']);
    Route::put('/{learningArea}', [LearningAreaController::class, 'update']);
    Route::delete('/{learningArea}', [LearningAreaController::class, 'destroy']);
});

Route::prefix('/strands')->group(function () {
    Route::get('/{strand}/sub-strands', [StrandController::class, 'getSubStrands']);
    Route::post('/', [StrandController::class, 'store']);
    Route::put('/{strand}', [StrandController::class, 'update']);
    Route::delete('/{strand}', [StrandController::class, 'destroy']);
});

Route::prefix('/sub-strands')->group(function () {
    Route::get('/{subStrand}/indicators', [SubStrandController::class, 'getIndicators']);
    Route::post('/', [SubStrandController::class, 'store']);
    Route::put('/{subStrand}', [SubStrandController::class, 'update']);
    Route::delete('/{subStrand}', [SubStrandController::class, 'destroy']);
});

Route::prefix('/indicators')->group(function () {
    Route::post('/', [IndicatorController::class, 'store']);
    Route::put('/{indicator}', [IndicatorController::class, 'update']);
    Route::delete('/{indicator}', [IndicatorController::class, 'destroy']);
});

Route::prefix('/students')->group(function () {
    Route::get('/{gradeId}', [StudentController::class, 'getByGradeId']);
    Route::get('/{student}/results', [StudentController::class, 'results']);
});

Route::prefix('/results')->group(function () {
    Route::post('/subject', [ResultController::class, 'storeSubject']);
    Route::post('/students/{student}', [ResultController::class, 'storeStudent']);
});

Route::prefix('/reports/exams/{exam}/grades/{grade}')->group(function () {
    Route::get('/preview', [ReportController::class, 'preview']);
    Route::post('/', [ReportController::class, 'store']);
});

Route::prefix('/summaries/exams/{exam}')->group(function () {
    Route::get('/preview', [SummaryController::class, 'preview']);
    Route::post('/', [SummaryController::class, 'store']);
});
