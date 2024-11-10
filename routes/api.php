<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\PlanoController;
use App\Http\Controllers\Api\CriptoController;

// Route::get('register', [UserController::class, 'register']);
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/hello', function () {
    return response()->json(['message' => 'hello']);
});

// usuarios
Route::post('/register', [UserController::class, 'store']);

Route::get('cpfUsed', [UserController::class, 'cpfIsInUse']);

Route::delete('/delete/{id}', [UserController::class, 'destroy']);

Route::post('/login', [UserController::class, 'login']);

Route::post('/user/log', [UserController::class, 'login'])->name('user.login'); //apagar depois

// Atualizar daddos do usuario
Route::put('/user/{id}/update-name', [UserController::class, 'updateName']);

Route::put('/user/{id}/update-email', [UserController::class, 'updateEmail']);

Route::put('/user/{id}/update-password', [UserController::class, 'updatePassword']);

Route::post('/user/{id}/update-image', [UserController::class, 'updateImage']);
// Cripto
Route::post('addCripto', [CriptoController::class, 'addCripto']);
Route::get('getCripto', [CriptoController::class, 'getCripto']);
Route::get('getAllCripto', [CriptoController::class, 'getAllCripto']);
Route::post('updCripto', [CriptoController::class, 'updCripto']);
Route::delete('dltCripto', [CriptoController::class, 'dltCripto']);

// Planos e views
Route::get('/user/view', [UserController::class, 'view']); // para fins de teste
Route::post('/user/create', [UserController::class, 'create'])->name('user.store'); //para fins de teste

Route::get('/user/login', [UserController::class, 'viewlog']);



Route::get('/busca', [UserController::class, 'busc']);

Route::get('/addplano', [PlanoController::class, 'store']); //adicionar os planos


Route::get('/planos/all', [PlanoController::class, 'planoAll']);

Route::delete('/end', [PlanoController::class, 'deleteAll']);


Route::post('/change-plan', [PlanoController::class, 'changeUserPlan']);

Route::get('/planos', [PlanoController::class, 'planoAll']);
Route::get('/updateimageview', [UserController::class, 'updateImageView']);