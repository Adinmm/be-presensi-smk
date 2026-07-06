<?php

use App\Http\Controllers\KelasController;
use Illuminate\Support\Facades\Route;


Route::post('/class', [KelasController::class, 'store']);
Route::patch('/class/{id}', [KelasController::class, 'update']);
Route::delete('/class/{id}', [KelasController::class, 'destroy']);
Route::get('/classes', [KelasController::class, 'index']);
Route::get('/classes/general', [KelasController::class, 'get']);
