<?php

use App\Http\Controllers\AttendencesController;
use Illuminate\Support\Facades\Route;


Route::post('/attendance', [AttendencesController::class, 'store']);
Route::get('/attendances', [AttendencesController::class, 'index']);
Route::patch('/attendance/{id}', [AttendencesController::class, 'update']);
