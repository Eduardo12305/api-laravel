<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ExpensesTypeService;

class ExpensesTypeController extends Controller
{
    protected $expensesTypeService;

    public function __construct(ExpensesTypeService $expensesTypeService)
    {
        $this->expensesTypeService = $expensesTypeService;
    }

    public function store(){
        $response = $this->expensesTypeService->addTypes();
        return response()->json();
    }

    public function deleteAll(){
        $response = $this->expensesTypeService->deleteAllTypes();
        return response()->json();
    }
}
