<?php

namespace App\Services;

use Kreait\Firebase\Contract\Database;
use App\Http\Requests\UserRequest;
use Kreait\Firebase\Database\Query\Sorter\OrderByChild;

class UserService
{
    protected $database;
    protected $tablename;

    public function __construct(Database $database)
    {
        $this->database = $database;
        $this->tablename = "contacts"; // Nome da tabela no Firebase
    }

    private function isValidCPF($cpf) {
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
        // dd($request);
        $register = $request->validated();
        $cpf = $register['cpf'];
        $date = $request['date'];
        $cep = $request['cep'];

        // Verificar se o CPF é válido
        if (!$this->isValidCPF($cpf)) {
            return [
                'status' => 'error',
                'message' => 'CPF inválido.',
            ];
        }

        // Verificar se já existe um usuário com o CPF fornecido
        $reference = $this->database->getReference($this->tablename);
        $existingUserSnapshot = $reference
            ->orderByChild('cpf')
            ->equalTo($cpf)
            ->getSnapshot();

        if ($existingUserSnapshot->numChildren() > 0) {
            return [
                'status' => 'error',
                'message' => 'Já existe um usuário com este CPF.',
            ];
        }

        // Verificar se a senha e a confirmação da senha coincidem
        if ($register['password'] !== $register['password_confirmation']) {
            return [
                'status' => 'error',
                'message' => 'As senhas não coincidem',
            ];
        }

        unset($register['password_confirmation']);

        // Adicionar os dados ao Firebase
        $this->database->getReference($this->tablename)->push($register);

        return [
            'status' => 'success',
            'message' => 'Usuário criado com sucesso!',
            'data' => $register,
        ];
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
    

}
