<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\PlanoController;
// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


Route::post('register', [UserController::class, 'register']);

Route::get('/hello', function () {
    return response()->json(['message' => 'hello']);
});

Route::post('register', [UserController::class, 'store']);


Route::get('/user/view', [UserController::class, 'view']); // para fins de teste
Route::post('/user/create', [UserController::class, 'create'])->name('user.store'); //para fins de teste

Route::get('/user/login', [UserController::class, 'viewlog']);
Route::post('/user/log', [UserController::class, 'login'])->name('user.login');

Route::delete('/delete/{id}', [UserController::class, 'destroy']);

Route::post('/login', [UserController::class, 'login']);


Route::get('/busca', [UserController::class, 'busc']);

Route::get('/addplano', [PlanoController::class, 'store']); //adicionar os planos


Route::get('/planos/all', [PlanoController::class, 'planoAll']);

Route::delete('/end', [PlanoController::class, 'deleteAll']);
