<?php

namespace App\Services;

use Kreait\Firebase\Contract\Database;
use App\Services\PlanoService;
use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserService
{
    protected $database;
    protected $tablename;
    protected $planoService;

    public function __construct(Database $database, PlanoService $planoService)
    {
        $this->database = $database;
        $this->tablename = "contacts"; // Nome da tabela no Firebase
        $this->planoService = $planoService;

    }

    private function isValidCPF($cpf)
    {
        // Remove caracteres não numéricos (pontos, hífens, etc.)
        $cpf = preg_replace('/\D/', '', $cpf);
        // dd($cpf);

        // Verifica se o CPF tem 11 dígitos
        if (strlen($cpf) != 11) {
            return false;
        }

        // Verifica se todos os dígitos são iguais (ex: 111.111.111-11)
        if (preg_match('/^(\d)\1{10}$/', $cpf)) {
            return false;
        }

        // Calcula o primeiro dígito verificador
        $sum1 = 0;
        for ($i = 0; $i < 9; $i++) {
            $sum1 += $cpf[$i] * (10 - $i);
        }
        $remainder1 = $sum1 % 11;
        $digit1 = ($remainder1 < 2) ? 0 : 11 - $remainder1;

        // Calcula o segundo dígito verificador
        $sum2 = 0;
        for ($i = 0; $i < 10; $i++) {
            $sum2 += $cpf[$i] * (11 - $i);
        }
        $remainder2 = $sum2 % 11;
        $digit2 = ($remainder2 < 2) ? 0 : 11 - $remainder2;

        // Verifica se os dígitos verificadores estão corretos
        return ($cpf[9] == $digit1 && $cpf[10] == $digit2);
    }

    public function cpfUsed($cpf){
        $reference = $this->database->getReference($this->tablename);
        $userSnapshot = $reference
        ->orderByChild('cpf')
        ->equalTo($cpf)
        ->getSnapshot();

        if ($userSnapshot->numChildren() > 0) {
            return response()->json([
                'inUse' => true,
            ], 201);
        }
        return response()->json([
            'inUse' => False,
        ], 201);

    }

    public function store(UserRequest $request)
    {
        $register = $request->validated();
        $cpf = $register['cpf'];
        $role = $register['role'] ?? 'cliente';
        
        
        
        // Validação do cpf
        if (!$this->isValidCPF($cpf)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Cpf inválido.',
            ], 400);
        }

        $register['cpf'] = preg_replace('/\D/', '', $register['cpf']);
        
        // Verificar se já existe um usuário com o CPF fornecido
        $reference = $this->database->getReference($this->tablename);
        $existingUserSnapshot = $reference
        ->orderByChild('cpf')
        ->equalTo($cpf)
        ->getSnapshot();
        
        if ($existingUserSnapshot->numChildren() > 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'Cpf está em uso.',
            ], 400);
        }

        // Verificar se a senha e a confirmação da senha coincidem
        if ($register['password'] !== $register['password_confirmation']) {
            return response()->json([
                'status' => 'error',
                'message' => 'As senhas não conhecidem.',
            ], 400);
        }
        
        unset($register['password_confirmation']);
        
        $register['role'] = $role;
        
        $register['password'] = Hash::make($register['password']);
        
        // Verificar imagem
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileData = file_get_contents($file);
            $base64 = 'data:image/' . $file->getClientOriginalExtension() . ';base64,' . base64_encode($fileData);
            
            // Adicionar a URL da imagem ao registro
            $register['image_b64'] = $base64;
        }
        
        $planId = $register['id_plano'] ?? 0;
        $planDetails = $this->planoService->getPlanById($planId);
        
        if(!$planDetails){
            return[
                'status' => 'error',
                'message' => 'Plano inválido'
            ];
        }

        $register['moeda'] = null;
        
        
        // Detalhes do plano adicionados
        $register['id_plano'] = $planId;
        $register['dt_venc'] = $this->planoService->calculateDueDate($planId);
        
          // Adicionar os dados ao Firebase
        $this->database->getReference($this->tablename)->push($register);

        return [
            'status' => 'success',
            'message' => 'Usuário criado com sucesso!',
            'data' => $register,
        ];
    }
    public function login($cpf, $password)
{
    if (!$this->isValidCPF($cpf)) {
        return [
            'status' => 'error',
            'message' => 'CPF inválido.',
        ];
    }
    $cpf = preg_replace('/\D/', '', $cpf);
    // Obter a referência da tabela de usuários
    $reference = $this->database->getReference($this->tablename);
    // Buscar usuário pelo CPF
    $snapshot = $reference->orderByChild('cpf')->equalTo($cpf)->getSnapshot();

    $userData = $snapshot->getValue();
    $userID = array_key_first($userData); // para pegar o ID
    $user = array_shift($userData); // Obtém o primeiro usuário da lista

    if ($user == null) {
        return [
            'status' => 'error',
            'message' => 'Usuário não encontrado.',
        ];
    }

    // Verificar a senha
    if (password_verify($password, $user['password'])) {
        return response()->json(array_merge(['id' => $userID], $user),200);
    } else {
        return response()->json([
            'message' => 'Usuário ou senha incorretos!'
        ],400);
    }
}


    public function index()
    {
        // Obter a referência da tabela
        $reference = $this->database->getReference($this->tablename);

        // Obter todos os dados da referência
        $snapshot = $reference->getSnapshot();

        // Verificar se há dados
        if (!$snapshot->exists()) {
            return [
                'status' => 'error',
                'message' => 'Nenhum usuário encontrado.',
            ];
        }

        // Obter todos os dados como um array
        $data = $snapshot->getValue();

        // Verificar se os dados são um array associativo
        if (!is_array($data)) {
            return [
                'status' => 'error',
                'message' => 'Formato de dados inválido.',
            ];
        }

        // Incluir IDs (chaves) no retorno, mantendo a estrutura original
        $formattedData = [];
        foreach ($data as $key => $value) {
            $formattedData[$key] = $value;
        }

        return [
            'status' => 'success',
            'data' => $formattedData,
        ];
    }

    public function delete($id)
    {
        // Obter a referência da tabela principal usando o ID do Firebase
        $reference = $this->database->getReference($this->tablename . '/' . $id);

        // Verificar se o usuário existe
        $snapshot = $reference->getSnapshot();
        if (!$snapshot->exists()) {
            return [
                'status' => 'error',
                'message' => 'Usuário não encontrado.',
            ];
        }

        // Excluir o usuário
        $reference->remove();

        return [
            'status' => 'success',
            'message' => 'Usuário excluído com sucesso.',
        ];
    }

    // UserService.php
    public function updateName($id, $name)
    {
        // Defina a referência no Firebase para o caminho correto
        $reference = $this->database->getReference($this->tablename . '/' . $id);
        
        // Verifica se o usuário existe no Firebase
        $user = $reference->getValue();
        
        if (!$user) {
            // Se o usuário não for encontrado, retorna a mensagem de erro
            return ['message' => 'Usuário não encontrado'];
        }
    
        // Atualizando o nome do usuário no Firebase
        try {
            $reference->update([
                'name' => $name
            ]);
        } catch (\Exception $e) {
            // Em caso de erro ao atualizar, captura a exceção
            return ['message' => 'Error updating name in Firebase', 'error' => $e->getMessage()];
        }
    
        // Retorna sucesso se a atualização for bem-sucedida
        return ['message' => 'Nome atualizado com sucesso'];
    }
    
    public function updateEmail($id,$email)
    {
        $reference = $this->database->getReference($this->tablename . '/' . $id);

        $user = $reference->getValue();

        if (!$user) {
            // Se o usuário não for encontrado, retorna a mensagem de erro
            return ['message' => 'Usuario não encontrado'];
        }

        try {
            $reference->update([
                'email' => $email
            ]);
        } catch(\Exception $e){
            return ['message' => 'Error updating email in firebase ','error' => $e->getMessage()];
        }

        return ['message' => 'E-mail atualizado com sucesso'];

    }

    public function updatePassword($id,$password)
    {
        $reference = $this->database->getReference($this->tablename . '/' . $id);

        $user = $reference->getValue();

        if (!$user) {
            // Se o usuário não for encontrado, retorna a mensagem de erro
            return ['message' => 'Uusario não encontrado'];
        }

        try {

            $hashedPassword = Hash::make($password);
            $reference->update([
                'password' => $hashedPassword
            ]);
        } catch(\Exception $e){
            return ['message' => 'Error updating password in firebase ','error' => $e->getMessage()];
        }

        return ['message' => 'Senha atualizado com sucesso'];

    }

    public function updateMoeda(Request $request, $id){

        $reference = $this->database->getReference($this->tablename . '/' . $id);
        $user = $reference->getSnapshot();

        if ($user->exists()) {
            $reference->update([
                'moeda' => $request->moeda,
            ]);
            return response()->json([
                'status' => 'success',
            ], 201);
        }
        return response()->json([
            'status' => 'error',
        ], 400);

    }

    public function updateImage(Request $request, $id)
{
    // Validação do arquivo de imagem

    $reference = $this->database->getReference($this->tablename . '/' . $id);
    $user = $reference->getSnapshot();

    if (!$user->exists()) {
        return response()->json([
            'status' => 'error',
            'message' => 'Usuário não encontrado.'
        ], 400);
    }
    if ($request->image != null) {
    // Verifica se o arquivo de imagem foi enviado
        // Atualizar o Firebase com a nova imagem
        $this->database->getReference($this->tablename . '/' . $id)->update([
            'image_b64' => $request->image,
        ]);
    }
    // Redireciona com a mensagem de sucesso
    return response()->json([
        'status' => 'success',
    ], 201);
}

public function checkUserExistence($idUser)
{
    // Referência ao usuário no Firebase
    $reference = $this->database->getReference($this->tablename . '/' . $idUser);

    // Obtém o snapshot do usuário
    $user = $reference->getSnapshot();

    // Verifica se o usuário existe
    if (!$user->exists()) {
        return response()->json([
            'status' => 'error',
            'message' => 'Usuário não encontrado.',
        ], 400); // Retorna o erro 404 se o usuário não existir
    }

    // Retorna true se o usuário existir
    return true;
}



}