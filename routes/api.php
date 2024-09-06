<?php

use App\Http\Controllers\Api\UserController;
use App\Http\Middleware\AuthUsers;
use Illuminate\Support\Facades\Route;


Route::post('register', [UserController::class, 'store'])->name('register');
Route::post('login', [UserController::class, 'login'])->name('login');

Route::middleware(AuthUsers::class)->group(function () {
    Route::delete('/users/{id}', [UserController::class, 'destroy']);
    Route::get('busca', [UserController::class, 'busc']);
});


Route::get('/cadastro', function() {
    return view('cadastro');
});

Route::get('/loginn', function(){
    return view('login');
})->name('loginview');

Route::get('/home', function(){
    return view('home');
})->name('home'); 
// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
