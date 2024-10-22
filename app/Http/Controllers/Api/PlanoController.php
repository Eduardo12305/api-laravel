<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PlanoService;
use Illuminate\Http\JsonResponse;

class PlanoController extends Controller
{
    protected $planoService;

    public function __construct(PlanoService $planoService)
    {
        $this->planoService = $planoService;
    }

    public function store(){
        $response = $this->planoService->addPlanos();
        return response()->json();
    }

    public function getPlano($id)
{
    $planDetails = $this->planoService->getPlanById($id);
    
    if (!$planDetails) {
        return response()->json([
            'status' => 'error',
            'message' => 'Plano inválido.',
        ], 404);
    }

    return response()->json([
        'status' => 'success',
        'data' => $planDetails,
    ]);
}

public function planoAll(): JsonResponse
{

    $plans = $this->planoService->getAllPlans();

    return response()->json([
        'status' => 'success',
        'data' => $plans
    ]);
}



// Não pega, olhar o planoService depois
public function deleteAll(): JsonResponse
{
    $response = $this->planoService->deleteAllPlans();

    return response()->json($response);
}


    

}
