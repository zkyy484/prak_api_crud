<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SiswaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::resource('siswa', SiswaController::class);
    Route::get('/siswa', [SiswaController::class, 'index']);
    Route::post('/siswa', [SiswaController::class, 'store']);
    Route::get('/siswa/{id}', [SiswaController::class, 'show']);
    Route::put('/siswa/{id}', [SiswaController::class, 'update']);
    Route::delete('/siswa/{id}', [SiswaController::class, 'destroy']);
});
