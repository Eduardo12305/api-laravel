<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;

Route::get('register', [UserController::class, 'register']);


Route::get('/', function () {
    return view('welcome');
});

// Route::post('register', [UserController::class, 'register']);

// Route::get('/hello', function () {
//     return response()->json(['message' => 'hello']);
// });

Route::post('register', [UserController::class, 'store']);
Route::delete('/delete/{id}', [UserController::class, 'destroy']);
Route::post('login', [UserController::class, 'login']);

Route::get('busca', [UserController::class, 'busc']);