<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ExpensesRequest ;
use App\Services\ExpensesService;
use App\Http\Requests\IdRequest;
use App\Services\UserService;

class ExpensesController extends Controller
{
    protected $expenseService;
    protected $UserService;

    public function __construct(ExpensesService $expenseService, UserService $UserService)
    {
        $this->expenseService = $expenseService;
        $this->UserService = $UserService;
    }

    public function create(ExpensesRequest $request)
    {
        $validatedData = $request->validated();
        $userExists = $this->UserService->checkUserExistence($request['idUser']);

        if ($userExists instanceof \Illuminate\Http\JsonResponse) {
            return $userExists; // Retorna a resposta de erro caso o usuário não exista
        }
        $expense = $this->expenseService->createExpense($validatedData);

        return response()->json([
            'success' => true,
            'data' => $expense,
        ], 201);
    }

    public function index($idUser)
    {
        $userExists = $this->UserService->checkUserExistence($idUser);

        if ($userExists instanceof \Illuminate\Http\JsonResponse) {
            return $userExists; // Retorna a resposta de erro caso o usuário não exista
        }
        $expenses = $this->expenseService->list($idUser);

        return response()->json([
            'success' => true,
            'data' => $expenses,
        ]);
    }

    public function update(ExpensesRequest $request, $id)
    {
        $validatedData = $request->validated();
        $userExists = $this->UserService->checkUserExistence($request['idUser']); 

        if ($userExists instanceof \Illuminate\Http\JsonResponse) {
            return $userExists; // Retorna a resposta de erro caso o usuário não exista
        }
        $expense = $this->expenseService->update($id, $validatedData);

        return response()->json([
            'success' => true,
            'data' => $expense,
        ]);
    }

    
    public function destroy(IdRequest $idDelete, $id)
    {
        $idDelete = $idDelete->validated();
        $userExists = $this->UserService->checkUserExistence($idDelete['id']); 

        if ($userExists instanceof \Illuminate\Http\JsonResponse) {
            return $userExists; // Retorna a resposta de erro caso o usuário não exista
        }
        $this->expenseService->delete($idDelete,$id);

        return response()->json([
            'success' => true,
            'message' => 'Despesa deletada com sucesso.',
        ]);
    }


}
