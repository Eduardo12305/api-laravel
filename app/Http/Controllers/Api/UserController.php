<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Requests\LoginRequest;
use App\Services\UserService;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    protected $userService;

    public function __construct(UserService $userService){
        $this->userService = $userService;
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

    public function updateName($id, $name)
{
    // Validação dos dados (sem a necessidade de verificar se o usuário existe no MySQL)
    $validated = Validator::make(compact('id', 'name'), [
        'id' => 'required|string',  // Verifica se o ID é válido como string (supondo que o ID seja string)
        'name' => 'required|string|max:255',
    ]);

    if ($validated->fails()) {
        return response()->json(['message' => 'Validation failed', 'errors' => $validated->errors()], 422);
    }

    // Chama o serviço de atualização
    $result = $this->userService->updateName($id, $name);

    // Caso o usuário não seja encontrado no Firebase
    if (isset($result['message']) && $result['message'] === 'User not found') {
        return response()->json($result, 404);  // Retorna 404 se o usuário não for encontrado no Firebase
    }

    // Caso tenha algum erro no Firebase durante a atualização
    if (isset($result['error'])) {
        return response()->json([
            'message' => 'Error updating name in Firebase',
            'error' => $result['error']
        ], 500);
    }

    // Caso a atualização tenha sido bem-sucedida
    return response()->json(['message' => 'Name updated successfully']);
}


}
