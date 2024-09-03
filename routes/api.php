<?php

use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AuthUsers;



Route::middleware([AuthUsers::class])->group(function () {
    // serve para não usar o middleware ->withoutMiddleware([AuthUsers::class]);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);
    Route::post('login', [UserController::class, 'login']);
    Route::get('busca', [UserController::class, 'busc']);
});
Route::post('register', [UserController::class, 'store'])->name("name"); 

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
