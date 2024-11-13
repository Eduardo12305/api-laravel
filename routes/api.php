<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\PlanoController;
use App\Http\Controllers\Api\CriptoController;
use App\Http\Controllers\Api\ExpensesTypeController;
use App\Http\Controllers\Api\ExpensesController;
use App\Http\Controllers\Api\ProfitsTypeController;
use App\Http\Controllers\Api\ProfitsController;
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


// planos
Route::get('/addplano', [PlanoController::class, 'store']); //adicionar os planos


Route::get('/planos/all', [PlanoController::class, 'planoAll']);

Route::delete('/end', [PlanoController::class, 'deleteAll']);


Route::post('/change-plan', [PlanoController::class, 'changeUserPlan']);

Route::get('/planos', [PlanoController::class, 'planoAll']);
Route::get('/updateimageview', [UserController::class, 'updateImageView']);

//  despesas ou Expenses
Route::post('/addDespesas', [ExpensesController::class, 'create']); // ok
Route::get('/getDespesas/{id}', [ExpensesController::class, 'index']); //ok
Route::put('/updateDespesas/{id}', [ExpensesController::class, 'update']); //ok 
Route::delete('/deleteDespesas/{id}', [ExpensesController::class, 'destroy']); //ok

// Lucros
Route::post('/addLucros', [ProfitsController::class, 'create']); //adicionar lucros e ultima rota feita/atualizada  Check
Route::get('/getLucros/{id}', [ProfitsController::class, 'index']); //listar lucros Check
Route::put('/updateLucros/{id}', [ProfitsController::class, 'update']); //atualizar lucros  Check
Route::delete('/deleteLucros/{id}', [ProfitsController::class, 'destroy']); //deletar lucros Check
// tipos de lucros
Route::get('/addtipos/lucros', [ProfitsTypeController::class, 'store']); //adicionar os tipos de lucros
 //adicionar despesas
Route::get('/deletetypes', [ExpensesTypeController::class, 'deleteAll']); //deletar todas as despesas

// tipos de gastos

Route::get('/addtipos', [ExpensesTypeController::class, 'store']); //adicionar os tipos de gastos