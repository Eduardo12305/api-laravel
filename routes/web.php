<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;

// Route::get('register', [UserController::class, 'register']);


Route::get('/', function () {
    return view('welcome');
});

// Route::post('register', [UserController::class, 'register']);

// Route::get('/hello', function () {
//     return response()->json(['message' => 'hello']);
// });

Route::post('register', [UserController::class, 'store']);


Route::get('/user/view', [UserController::class, 'view']);
Route::post('/user/create', [UserController::class, 'create'])->name('user.store');

Route::delete('/delete/{id}', [UserController::class, 'destroy']);
Route::post('login', [UserController::class, 'login']);

Route::get('busca', [UserController::class, 'busc']);

Route::get('/token', function (Request $request) {
    $token = $request->session()->token();
 
    $token = csrf_token();
 
    // ...
});