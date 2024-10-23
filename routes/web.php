<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\UserController;
// Route::get('register', [UserController::class, 'register']);


Route::get('/', function () {
    return view('welcome');
});
