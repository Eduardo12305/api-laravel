<?php

use App\Http\Controllers\Api\UserController;
use App\Http\Middleware\AuthUsers;
use Illuminate\Support\Facades\Route;


Route::post('register', [UserController::class, 'store']);
Route::post('login', [UserController::class, 'login']);

Route::middleware(AuthUsers::class)->group(function () {
    Route::delete('/users/{id}', [UserController::class, 'destroy']);
    Route::get('busca', [UserController::class, 'busc']);
});

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
