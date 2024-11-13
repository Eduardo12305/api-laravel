<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\profitsResquest;
use App\Services\ProfitsService;
use App\Http\Requests\IdRequest;
use App\Services\UserService;
class ProfitsController extends Controller
{
    protected $profitService;
    protected $UserService;

    public function __construct(ProfitsService $profitService, UserService $UserService)
    {
        $this->profitService = $profitService;
        $this->UserService = $UserService;
    }

    public function create(profitsResquest $request)
    {
        // Valida os dados da requisição
        $validatedData = $request->validated();
        // Verifica se o usuário existe
        $userExists = $this->UserService->checkUserExistence($validatedData['idUser']);

        if ($userExists instanceof \Illuminate\Http\JsonResponse) {
            return $userExists; // Retorna a resposta de erro caso o usuário não exista
        }

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

    public function index($idUser)
    {
        try {

            $userExists = $this->UserService->checkUserExistence($idUser);

            if ($userExists instanceof \Illuminate\Http\JsonResponse) {
                return $userExists; // Retorna a resposta de erro caso o usuário não exista
            }

            // Chama o método list passando o idUser para obter a lista de lucros para o usuário
            $profits = $this->profitService->list($idUser);

            // Retorna a resposta com sucesso
            return response()->json([
                'success' => true,
                'data' => $profits,
            ]);
        } catch (\Exception $e) {
            // Em caso de erro, retorna uma resposta de erro
            return response()->json([
                'success' => false,
                'message' => 'Erro ao obter lucros: ' . $e->getMessage(),
            ], 500); // Código HTTP 500 para erro do servidor
        }
    }

    public function update(profitsResquest $request, $id)
    {
        // Valida os dados da requisição
        $validatedData = $request->validated();
        // Verifica se o usuário existe
        $userExists = $this->UserService->checkUserExistence($validatedData['idUser']);

        if ($userExists instanceof \Illuminate\Http\JsonResponse) {
            return $userExists; // Retorna a resposta de erro caso o usuário não exista
        }

        // Chama o método update passando o id do lucro e os dados validados
        $profit = $this->profitService->update($id,$validatedData);

        // Retorna a resposta com sucesso
        return response()->json([
            'success' => true,
            'data' => $profit,
        ]);

    }

    public function destroy(IdRequest $idDelete,$id)
    {
        try {
            // Chama o método delete para excluir o lucro com o id fornecido
            $idDelete = $idDelete->validated();
            $userExists = $this->UserService->checkUserExistence($idDelete['idUser']);

            if ($userExists instanceof \Illuminate\Http\JsonResponse) {
                return $userExists; // Retorna a resposta de erro caso o usuário não exista
            }
            $this->profitService->delete($idDelete,$id);
            // Retorna a resposta com sucesso
            return response()->json([
                'success' => true,
                'message' => 'Lucro deletado com sucesso.',
            ]);
        } catch (\Exception $e) {
            // Em caso de erro, retorna uma resposta de erro
            return response()->json([
                'success' => false,
                'message' => 'Erro ao deletar lucro: ' . $e->getMessage(),
            ], 500); // Código HTTP 500 para erro do servidor
        } 
    }
    
}
