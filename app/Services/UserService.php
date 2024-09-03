<?php

namespace App\Services;

use Kreait\Firebase\Contract\Database;
use App\Http\Requests\UserRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
class UserService
{
    protected $database;
    protected $tablename;

    public function __construct(Database $database)
    {
        $this->database = $database;
        $this->tablename = "contacts"; // Nome da tabela no Firebase

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

    public function store(UserRequest $request)
{
    $register = $request->validated();
    $cpf = $register['cpf'];

    // Validação do CPF
    if (!$this->isValidCPF($cpf)) {
        return response()->json(['status' => 'error', 'message' => 'CPF inválido.'], 400);
    }

    // Verificar se já existe um usuário com o CPF fornecido
    try {
        $existingUserSnapshot = $this->database->getReference($this->tablename)
            ->orderByChild('cpf')
            ->equalTo($cpf)
            ->getSnapshot();
        
        if ($existingUserSnapshot->numChildren() > 0) {
            return response()->json(['status' => 'error', 'message' => 'Já existe um usuário com este CPF.'], 400);
        }
    } catch (\Exception $e) {
        return response()->json(['status' => 'error', 'message' => 'Erro ao verificar CPF: ' . $e->getMessage()], 500);
    }

    // Verificar se a senha e a confirmação da senha coincidem
    if ($register['password'] !== $register['password_confirmation']) {
        return response()->json(['status' => 'error', 'message' => 'As senhas não coincidem'], 400);
    }

    unset($register['password_confirmation']);
    $register['password'] = Hash::make($register['password']);

    // Atribuição de papel ao novo usuário
    $currentUser = auth()->user();
    $currentUserRole = $currentUser->role ?? null;

    if ($currentUserRole === 'super_admin') {
        // Super_admin pode definir o papel do novo usuário
        $register['role'] = $request->input('role', 'cliente'); // Role pode ser 'admin' ou 'cliente'
    } elseif ($currentUserRole === 'admin') {
        // Admin pode criar apenas um cliente
        $register['role'] = 'cliente';
    } elseif ($currentUserRole === null) {
        // Se não há um usuário logado, o papel é 'cliente'
        $register['role'] = 'cliente';
    } else {
        // Usuário não autorizado a criar novos usuários
        return response()->json(['status' => 'error', 'message' => 'Não autorizado a criar usuários.'], 403);
    }

    // Adicionar o usuário ao banco de dados
    try {
        $this->database->getReference($this->tablename)->push($register);
    } catch (\Exception $e) {
        return response()->json(['status' => 'error', 'message' => 'Erro ao registrar usuário: ' . $e->getMessage()], 500);
    }

    return response()->json(['status' => 'success', 'message' => 'Usuário registrado com sucesso.'], 201);
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

}