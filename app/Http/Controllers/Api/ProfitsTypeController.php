<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ProfitsTypeService;
class ProfitsTypeController extends Controller
{
    protected $profitsTypeService;

    public function __construct(ProfitsTypeService $profitsTypeService)
    {
        $this->profitsTypeService = $profitsTypeService;
    }

    public function store(){
        $response = $this->profitsTypeService->addTypeProfits();
        return response()->json($response);
    }
}
