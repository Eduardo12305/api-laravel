<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\PlanoController;
use App\Http\Controllers\Api\CriptoController;
use App\Http\Controllers\Api\ExpensesTypeController;
use App\Http\Controllers\Api\ExpensesController;
use App\Http\Controllers\Api\ProfitsTypeController;
use App\Http\Controllers\Api\ProfitsController;

Route::get('/hello', function () {
    return response()->json(['message' => 'hello']);
});

// usuarios
Route::post('/register', [UserController::class, 'store']);

Route::post('cpfUsed', [UserController::class, 'cpfIsInUse']);

Route::delete('/delete/{id}', [UserController::class, 'destroy']);

Route::post('/login', [UserController::class, 'login']);

Route::post('/user/log', [UserController::class, 'login'])->name('user.login'); //apagar depois

Route::put('update-plan/{idUser}',[UserController::class, 'updatePlano']);
// Atualizar daddos do usuario
Route::put('/user/{id}/update-name', [UserController::class, 'updateName']);

Route::put('/user/{id}/update-email', [UserController::class, 'updateEmail']);

Route::put('/user/{id}/update-password', [UserController::class, 'updatePassword']);

Route::put('/user/{id}/update-moeda', [UserController::class, 'updateMoeda']);

Route::post('/user/{id}/update-image', [UserController::class, 'updateImage']);
// Cripto
Route::post('addCripto', [CriptoController::class, 'addCripto']);
Route::post('getCripto', [CriptoController::class, 'getCripto']);
Route::post('getAllCripto', [CriptoController::class, 'getAllCripto']);
Route::put('updCripto', [CriptoController::class, 'updCripto']);
Route::delete('dltCripto', [CriptoController::class, 'dltCripto']);

// busca de ususarios
Route::get('/busca', [UserController::class, 'busc']);


// planos
Route::post('/addplano', [PlanoController::class, 'store']); //adicionar os planos


Route::get('/planos/all', [PlanoController::class, 'planoAll']);

Route::post('/change-plan/{planId}', [PlanoController::class, 'changePlan']);

//  despesas ou Expenses
Route::post('/addDespesas', [ExpensesController::class, 'create']); // ok
Route::get('/getDespesas/{id}', [ExpensesController::class, 'index']); //ok
Route::put('/updateDespesas/{id}', [ExpensesController::class, 'update']); //ok 
Route::delete('/deleteDespesas', [ExpensesController::class, 'destroy']); //ok

// Lucros
Route::post('/addLucros', [ProfitsController::class, 'create']); //adicionar lucros e ultima rota feita/atualizada  Check
Route::get('/getLucros/{id}', [ProfitsController::class, 'index']); //listar lucros Check
Route::put('/updateLucros/{id}', [ProfitsController::class, 'update']); //atualizar lucros  Check
Route::delete('/deleteLucros/{id}', [ProfitsController::class, 'destroy']); //deletar lucros Check

// tipos de lucros
Route::post('/addTipo/lucro', [ProfitsTypeController::class, 'store']); //adicionar os tipos de lucros  Check
Route::get('/getTipo/lucros/{idUser}', [ProfitsTypeController::class, 'index']); //listar os tipos de lucros Check
Route::put('/updateTipo/lucros/{id}', [ProfitsTypeController::class, 'update']); //atualizar os tipos de lucros Check
Route::delete('/deleteTipo/lucros/{idUser}', [ProfitsTypeController::class, 'destroy']); //deletar os tipos de lucros Check

// tipos de gastos
Route::post('/addtipo/despesa', [ExpensesTypeController::class, 'store']); //adicionar os tipos de gastos Check
Route::get('/gettipo/despesa/{idUser}', [ExpensesTypeController::class, 'index']); //listar os tipos de gastos Check
Route::put('/updatetipo/despesa/{id}', [ExpensesTypeController::class, 'update']); //atualizar os tipos de gastos Check
Route::delete('/deletetipo/despesa/{idUser}', [ExpensesTypeController::class, 'destroy']); //deletar os tipos de gastos Check