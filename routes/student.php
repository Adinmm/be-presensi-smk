<?php


use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

Route::post('/student', [StudentController::class, 'store']);
Route::get('/students', [StudentController::class, 'index']);
Route::patch('/student/{id}', [StudentController::class, 'update']);
