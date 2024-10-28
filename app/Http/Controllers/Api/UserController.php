<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Requests\LoginRequest;
use App\Services\UserService;
class UserController extends Controller
{

    protected $userService;

    public function __construct(UserService $userService){
        $this->userService = $userService;
    }


    public function create(UserRequest $request){
        $response = $this->userService->store($request);
        return response()->json($response);
    }

    public function view(){
        return view('imgteste');
    }

    public function viewlog(){
        return view('lgnteste');
    }

    public function store(UserRequest $request) {
        //  CRIAR USUARIO
        // dd($request);
        $response = $this->userService->store($request);

        return response()->json($response);
    }

    public function login(LoginRequest $request)
    {
        $validated = $request->validated(); // Obtém os dados validados

        $cpf = $validated['cpf'];
        $password = $validated['password'];

        $result = $this->userService->login($cpf, $password);

        return $result;
        
    }


    public function destroy($id)
{
    // Chama o método delete do UserService com o ID do Firebase
    $del = $this->userService->delete($id);

    // Verifica o status retornado pelo serviço e retorna a resposta apropriada
    if ($del['status'] === 'error') {
        return response()->json(['message' => $del['message']], 404);
    }

    return response()->json(['message' => $del['message']], 200);
}

    // Colocar para AdminControll se tiver
    public function busc(){
        $result = $this->userService->index();

        if ($result['status'] == 'success'){
            return response()->json($result['data']);
        }

        return response()->json(['message' => $result['message']], 404);
    }

}
