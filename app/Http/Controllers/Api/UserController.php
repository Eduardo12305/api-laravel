<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Requests\LoginRequest;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    protected $userService;
    protected $tablename;


    public function __construct(UserService $userService){
        $this->userService = $userService;
        $this->tablename = "contacts"; // Nome da tabela no Firebase

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
    
    public function updateName(Request $req, $id)
{
    // Obtendo o "name" do corpo da requisição
    $name = $req->name;

    // Validação dos dados
    $validated = Validator::make(compact('id', 'name'), [
        'id' => 'required|string',  // O id deve ser passado via URL e ser uma string
        'name' => 'required|string|max:255',  // O name vem do corpo da requisição
    ]);

    if ($validated->fails()) {
        // Se a validação falhar, retorna erro 422
        return response()->json(['message' => 'Validation failed', 'errors' => $validated->errors()], 422);
    }

    // Chama o serviço de atualização com id e name
    $result = $this->userService->updateName($id, $name);

    // Caso o usuário não seja encontrado no Firebase
    if (isset($result['message']) && $result['message'] === 'User not found') {
        return response()->json($result, 404);  // Retorna 404 se o usuário não for encontrado
    }

    // Caso tenha algum erro no Firebase durante a atualização
    if (isset($result['error'])) {
        return response()->json([
            'message' => 'Error updating name in Firebase',
            'error' => $result['error']
        ], 500);
    }

    // Caso a atualização tenha sido bem-sucedida
    return response()->json($result);
}

public function cpfIsInUse(Request $req){
    $cpf = $req->cpf;

    return $this->userService->cpfUsed($cpf);
}


public function updateEmail(Request $req, $id)
{
    // Obtendo o "email" do corpo da requisição
    $email = $req->email;
    
    // Validação dos dados
    $validated = Validator::make(compact('id', 'email'), [
        'id' => 'required|string',  
        'email' => 'required|email|max:255',  
    ]);

    if ($validated->fails()) {
        // Se a validação falhar, retorna erro 422
        return response()->json(['message' => 'Validation failed', 'errors' => $validated->errors()], 422);
    }

    // Chama o serviço de atualização com id e email
    $result = $this->userService->updateEmail($id, $email);

    // Caso o usuário não seja encontrado no Firebase
    if (isset($result['message']) && $result['message'] === 'User not found') {
        return response()->json($result, 404);  // Retorna 404 se o usuário não for encontrado
    }

    // Caso tenha algum erro no Firebase durante a atualização
    if (isset($result['error'])) {
        return response()->json([
            'message' => 'Error updating name in Firebase',
            'error' => $result['error']
        ], 500);
    }

    // Caso a atualização tenha sido bem-sucedida
    return response()->json($result);
}

public function updatePassword(Request $req, $id)
{
    // Obtendo o "password" do corpo da requisição
    $password = $req->password;
    
    // Validação dos dados
    $validated = Validator::make(compact('id', 'password'), [
        'id' => 'required|string',  
        'password' => ['required', Password::min(8)->letters()->numbers()->mixedCase()->symbols()],  // Regras de senha mais fortes
    ]);

    if ($validated->fails()) {
        // Se a validação falhar, retorna erro 422
        return response()->json(['message' => 'Validation failed', 'errors' => $validated->errors()], 422);
    }

    // Chama o serviço de atualização com id e password
    $result = $this->userService->updatePassword($id, $password);

    // Caso o usuário não seja encontrado no Firebase
    if (isset($result['message']) && $result['message'] === 'User not found') {
        return response()->json($result, 404);  // Retorna 404 se o usuário não for encontrado
    }

    // Caso tenha algum erro no Firebase durante a atualização
    if (isset($result['error'])) {
        return response()->json([
            'message' => 'Error updating name in Firebase',
            'error' => $result['error']
        ], 500);
    }

    // Caso a atualização tenha sido bem-sucedida
    return response()->json($result);
}

public function updateImage( Request $request, $id)
{
    // Chamar a função updateImage do UserService
    $response = $this->userService->updateImage($request, $id);

    // Retornar a resposta
    return response()->json($response);
}

public function updateImageView(){
    return view('imageupdate');}

}
