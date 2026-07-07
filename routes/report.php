<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;



Route::get('/reports', [ReportController::class, 'index']);
Route::get('/dashboard', [ReportController::class, 'dashboard']);
Route::get('/backup-data', [ReportController::class, 'backupData']);

