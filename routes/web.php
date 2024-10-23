<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// Route::get('register', [UserController::class, 'register']);


Route::get('/', function () {
    return view('welcome');
});
