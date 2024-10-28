<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PlanoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

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


public function changeUserPlan($planId, $confirmation): JsonResponse {
    if (!$confirmation) {
        return response()->json([
            'status' => 'error',
            'message' => 'Confirmação necessária para mudar o plano.'
        ], 400);
    }

    $userId = Auth::id();
    if (!$userId) {
        return response()->json([
            'status' => 'error',
            'message' => 'Usuário não autenticado.'
        ], 401);
    }

    $result = $this->planoService->changeUserPlan($userId, $planId);

    return response()->json($result, $result['status'] === 'success' ? 200 : 500);
}

// Não pega, olhar o planoService depois
public function deleteAll(): JsonResponse
{
    $response = $this->planoService->deleteAllPlans();

    return response()->json($response);
}


    

}
