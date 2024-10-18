<?php

use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::post('register', [UserController::class, 'store']);
Route::delete('/delete/{id}', [UserController::class, 'destroy']);
Route::post('login', [UserController::class, 'login']);

Route::get('busca', [UserController::class, 'busc']);
// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
