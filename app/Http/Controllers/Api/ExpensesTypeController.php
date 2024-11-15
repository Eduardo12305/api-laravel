<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ExpensesTypeService;
use App\Http\Requests\IdRequest;
use App\Http\Requests\TypeExpensesRequest as expensesType;

class ExpensesTypeController extends Controller
{
    protected $expensesTypeService;

    public function __construct(ExpensesTypeService $expensesTypeService)
    {
        $this->expensesTypeService = $expensesTypeService;
    }

    public function store(expensesType $request){
        $data = $request->all();
        $response = $this->expensesTypeService->addType($data);
        return response()->json($response);
    }

    public function index($idUser){
        $response = $this->expensesTypeService->getAllTypes($idUser);
        return response()->json($response);
    }

    public function update(expensesType $request, $id){
        $data = $request->all();
        $response = $this->expensesTypeService->updateType($id, $data);
        return response()->json($response);
    }

    public function destroy(IdRequest $idRequest, $idUser){
        $id = $idRequest['id'];
        $response = $this->expensesTypeService->deleteType($id, $idUser);
        return response()->json($response);
    }
}
