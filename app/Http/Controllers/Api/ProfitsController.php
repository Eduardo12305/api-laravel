<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\profitsResquest;
use App\Services\ProfitsService;

class ProfitsController extends Controller
{
    protected $profitService;

    public function __construct(ProfitsService $profitService)
    {
        $this->profitService = $profitService;
    }

    public function create(profitsResquest $request)
    {
        // Valida os dados da requisição
        $validatedData = $request->validated();

        // Extrai o idUser (assumindo que é parte dos dados validados)
        $idUser = $validatedData['idUser'];

        // Remove o idUser dos dados do lucro (não precisamos passá-lo novamente)
        unset($validatedData['idUser']);

        // Chama o método createProfit, passando os dados do lucro (sem idUser)
        $profit = $this->profitService->createProfit($idUser, $validatedData);

        // Retorna a resposta com sucesso
        return response()->json([
            'success' => true,
            'data' => $profit,
        ], 201);
    }


    
}
