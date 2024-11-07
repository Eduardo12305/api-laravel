<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\PlanoController;
use App\Http\Controllers\Api\CriptoController;
// Route::get('register', [UserController::class, 'register']);


Route::get('/', function () {
    return view('welcome');
});


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


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

Route::post('addCripto', [CriptoController::class, 'addCripto']);
Route::get('getCripto', [CriptoController::class, 'getCripto']);
Route::get('getAllCripto', [CriptoController::class, 'getAllCripto']);
Route::post('updCripto', [CriptoController::class, 'updCripto']);
Route::delete('dltCripto', [CriptoController::class, 'dltCripto']);



Route::get('/addplano', [PlanoController::class, 'store']); //adicionar os planos


Route::get('/planos/all', [PlanoController::class, 'planoAll']);

Route::delete('/end', [PlanoController::class, 'deleteAll']);


Route::post('/change-plan', [PlanoController::class, 'changeUserPlan']);