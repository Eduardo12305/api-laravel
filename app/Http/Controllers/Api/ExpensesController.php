<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ExpensesRequest ;
use App\Services\ExpensesService;

class ExpensesController extends Controller
{
    protected $expenseService;

    public function __construct(ExpensesService $expenseService)
    {
        $this->expenseService = $expenseService;
    }

    public function create(ExpensesRequest $request)
    {
        // dd($request);
        $validatedData = $request->validated();
        $expense = $this->expenseService->createExpense($validatedData);

        return response()->json([
            'success' => true,
            'data' => $expense,
        ], 201);
    }

    public function index()
    {
        $expenses = $this->expenseService->list();

        return response()->json([
            'success' => true,
            'data' => $expenses,
        ]);
    }

    public function update(ExpensesRequest $request, $id)
    {
        $validatedData = $request->validated();
        $expense = $this->expenseService->update($id, $validatedData);

        return response()->json([
            'success' => true,
            'data' => $expense,
        ]);
    }

    
    public function destroy($id)
    {
        $this->expenseService->delete($id);

        return response()->json([
            'success' => true,
            'message' => 'Despesa deletada com sucesso.',
        ]);
    }


}
