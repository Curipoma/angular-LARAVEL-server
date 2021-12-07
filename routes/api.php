<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\PeliculasController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', [UserController::class, 'login']);
Route::post('/users/store', [UserController::class, 'store']);
Route::post('/logout', [UserController::class, 'logout']);


Route::get('/films', [PeliculasController::class, 'index']);

Route::group(['middleware' => ['auth:sanctum','cors']], function() {
    Route::get('/films/show/{id}', [PeliculasController::class, 'show']);
    Route::post('/films/store', [PeliculasController::class, 'store']);
    Route::put('/films/update/{id}', [PeliculasController::class, 'update']);
    Route::delete('/films/destroy/{id}', [PeliculasController::class, 'destroy']);
    
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/show/{id}', [UserController::class, 'show']);
    Route::put('/users/update/{id}', [UserController::class, 'update']);
    Route::delete('/users/destroy/{id}', [UserController::class, 'destroy']);
});


// API Films

// {
//     "name":"Saw 4",
//     "price":"7.00",
//     "duration":"1h 30min"
// }
