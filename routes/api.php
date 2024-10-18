<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;

Route::post('register', [UserController::class, 'store']);
Route::delete('/delete/{id}', [UserController::class, 'destroy']);
Route::post('login', [UserController::class, 'login']);

Route::get('busca', [UserController::class, 'busc']);
// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
