<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Requests\LoginRequest;
use App\Services\UserService;
class UserController extends Controller
{

    // protected $database;

    // protected $tablename;

    // public function __construct(Database $database){
    //     $this->database = $database;
    //     $this->tablename = "contacts";
    // }

    // public function store(UserRequest $request)
    // {
        // $post = $this->database->getReference($this->tablename)->push($request->validated());   

        
        // return [
        //     "status" => "success",
        //     "message" => "Usuário criado com sucesso!",
        //     "data" => $request->validated(),
        // ];

    //     $register = $request->validated();
        
    //     if ($register['password'] !== $register['password_confirmation']) {
    //         return [
    //             'status' => 'error',
    //             'message' => 'As senhas não coincidem',
    //         ];
    //     }
    //     unset($register['password_confirmation']);

    //     $this->database->getReference($this->tablename)->push($register);

    //     return [
    //         'status' => 'success',
    //         'message' => 'usuario criado',
    //         'data' => $register,
    //     ];

    // }

    protected $userService;

    public function __construct(UserService $userService){
        $this->userService = $userService;
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

        if ($result['status'] === 'success') {
            return response()->json([
                'status' => 'success',
                'message' => 'Usuário autenticado com sucesso.',
                'user' => $result['user']
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => $result['message']
            ], 401);
        }
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
