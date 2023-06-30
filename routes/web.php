<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ResultController;
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

Route::get('/', function() {
    return view('welcome');
});

Route::prefix('/dashboard')->middleware(['auth', 'verified'])->name('admin')->group(function() {
    Route::get('/', [DashboardController::class, 'index'])->name('.dashboard');

    Route::prefix('/marks')->name('.assessment')->group(function() {
        Route::get('/student', [ResultController::class, 'createOrEditStudent'])->name('.student');
        Route::get('/subject', [ResultController::class, 'createOrEditSubject'])->name('.subject');
    });

    Route::prefix('/attendances')->name('.attendances')->group(function() {
        Route::get('/', [AttendanceController::class, 'createOrEdit']);
    });

    Route::prefix('/reports')->name('.reports')->group(function() {
        Route::get('/', [ReportController::class, 'index']);
    });
});

require __DIR__.'/auth.php';
