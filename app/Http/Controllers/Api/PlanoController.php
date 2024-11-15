<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PlanoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\planoRequest;

class PlanoController extends Controller
{
    protected $planoService;

    public function __construct(PlanoService $planoService)
    {
        $this->planoService = $planoService;
    }

    public function store(planoRequest $req){
        $plan = $req->validated();
        $response = $this->planoService->addPlano($plan);
        return response()->json($response);
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
    
    // Debugging: veja o que está sendo retornado
  

    return response()->json([
        'status' => 'success',
        'data' => $plans
    ]);
}


public function changePlan($planId, planoRequest $data): JsonResponse {

    $data = $data->validated();
    $result = $this->planoService->changePlan( $planId, $data);

    return response()->json($result, $result['status'] === 'success' ? 200 : 500);
}

// Não pega, olhar o planoService depois
public function deleteAll(): JsonResponse
{
    $response = $this->planoService->deleteAllPlans();

    return response()->json($response);
}

}
