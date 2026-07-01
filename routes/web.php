<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttendencesController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StudentController;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/api/attendance', [AttendencesController::class, 'store']);
Route::get('/api/attendances', [AttendencesController::class, 'index']);

Route::post('/api/class', [KelasController::class, 'store']);
Route::patch('/api/class/{id}', [KelasController::class, 'update']);
Route::get('/api/classes', [KelasController::class, 'index']);
Route::get('/api/classes/general', [KelasController::class, 'get']);

Route::get('/api/reports', [ReportController::class, 'index']);
Route::get('/api/dashboard', [ReportController::class, 'dashboard']);

Route::post('/api/student', [StudentController::class, 'store']);
Route::get('/api/students', [StudentController::class, 'index']);
Route::patch('/api/student/{id}', [StudentController::class, 'update']);



