<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\FilmsController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/users/login', [UserController::class, 'login']);
Route::post('/users/store', [UserController::class, 'store']);
Route::get('/films', [FilmsController::class, 'index'])->name('films');

Route::group(['middleware' => ['auth:sanctum']], function() {
    Route::get('/films/show/{id}', [FilmsController::class, 'show']);
    Route::post('/films/store', [FilmsController::class, 'store']);
    Route::post('/films/update/{id}', [FilmsController::class, 'update']);
    Route::delete('/films/destroy/{id}', [FilmsController::class, 'destroy']);
    Route::get('/films/download/image/{id}', [FilmsController::class, 'download_image']);
    Route::get('/films/download/video/{id}', [FilmsController::class, 'download_video']);
    
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/show/{id}', [UserController::class, 'show']);
    Route::put('/users/update/{id}', [UserController::class, 'update']);
    Route::delete('/users/destroy/{id}', [UserController::class, 'destroy']);
    Route::get('/users/logout/{id}', [UserController::class, 'logout']);
});
