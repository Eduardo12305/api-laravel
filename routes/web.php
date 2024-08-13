<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;

Route::get('register', [UserController::class, 'register']);


// Route::get('/', function () {
//     return view('welcome');
// });

// Route::post('register', [UserController::class, 'register']);
