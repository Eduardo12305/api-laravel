<?php

namespace App\Services;
use Kreait\Firebase\Contract\Database;
use Kreait\Firebase\Exception\DatabaseException;

class ProfitsService
{
    protected $database;
    protected $tablename;

    public function __construct(Database $database)
    {
        $this->database = $database;
        $this->tablename = "profits"; // Nome da tabela no Firebase
    }

    public function createProfit($idUser, array $data)
    {
        try {
            // Modifica o nome da tabela diretamente para "contracts" quando necessário
            $this->tablename = "contacts"; // Aqui definimos que vamos usar a tabela 'contracts'

            // Adiciona a data de início automaticamente com a data atual
            $data['dt_init'] = now(); // Preenche automaticamente a data e hora atuais
            $data['dt_update'] = now();

            // Referência para a coleção de contratos do usuário
            $ref = $this->database->getReference($this->tablename . '/' . $idUser . '/profits');

            // Cria um novo lucro dentro da coleção de contratos do usuário
            $newContract = $ref->push($data);

            // Obtém o ID do contrato inserido (a última parte da URL da referência)
            $contractID = substr($newContract->getKey(), strrpos($newContract->getKey(), '/') + 1);

            // Adiciona o ID do usuário e o ID do contrato dentro da referência do contrato
            $this->database->getReference($this->tablename . '/' . $idUser . '/' . $contractID . '/idUser')->set($idUser);
            $this->database->getReference($this->tablename . '/' . $idUser . '/' . $contractID . '/ID')->set($contractID);

            // Retorna o contrato criado
            return [
                'status' => 'success',
                'ID' => $contractID,
                'contract' => $newContract->getValue() // Retorna os dados do contrato criado
            ];
        } catch (DatabaseException $e) {
            // Em caso de erro, lança uma exceção
            throw new \Exception("Erro ao criar contrato: " . $e->getMessage());
        }
    }

    

    public function list($idUser)
{
    try {
        // Modifica o nome da tabela para "contracts", conforme usado no createProfit
        $this->tablename = "contacts"; // Tabela 'contracts'

        // Referência para a coleção de lucros (profits) de um usuário específico
        $ref = $this->database->getReference($this->tablename . '/' . $idUser . '/profits');

        // Obtém todos os lucros (profits) associados ao usuário
        $profits = $ref->getValue();

        // Retorna os lucros ou um array vazio, caso não existam lucros
        return $profits ? $profits : [];
    } catch (DatabaseException $e) {
        // Em caso de erro, lança uma exceção
        throw new \Exception("Erro ao listar lucros: " . $e->getMessage());
    }
}


    public function update($id, array $data)
    {
        try {
            $this->tablename = "contacts";
            $idUser = $data['idUser'];
            $data['dt_update'] = now(); // Preenche automaticamente a data e hora atuais
            $ref = $this->database->getReference($this->tablename . '/'. $idUser . '/profits/' . $id ); // Referência para a despesa específica
            $ref->update($data); // Atualiza os dados da lucro

            return $ref->getValue(); // Retorna a lucro atualizada
        } catch (DatabaseException $e) {
            throw new \Exception("Erro ao atualizar lucro: " . $e->getMessage());
        }
    }

   
    public function delete(array $idDelete,$id)
    {
        try {
            $this->tablename = "contacts";
            $idUser = $idDelete['idUser'];
            $ref = $this->database->getReference($this->tablename. '/'. $idUser.'/profits/'. $id); // Referência para a despesa específica
            $ref->remove(); // Deleta a lucro
        } catch (DatabaseException $e) {
            throw new \Exception("Erro ao deletar lucro: " . $e->getMessage());
        }
    }
}
