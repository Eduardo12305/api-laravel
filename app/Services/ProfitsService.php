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
            // Adiciona a data de início automaticamente com a data atual
            $data['dt_init'] = now(); // Preenche automaticamente a data e hora atuais

            // Referência para a coleção de contratos (contracts) do usuário
            $ref = $this->database->getReference($this->tablename . '/' . $idUser . '/contracts');

            // Cria um novo lucro dentro dos contratos do usuário
            $newContract = $ref->push($data);

            // Obtém o ID do contrato inserido (a última parte da URL da referência)
            $contractID = substr($newContract->getKey(), strrpos($newContract->getKey(), '/') + 1);

            // Adiciona o ID do usuário e o ID do contrato dentro da referência do contrato
            $this->database->getReference($this->tablename . '/' . $idUser . '/contracts/' . $contractID . '/idUser')->set($idUser);
            $this->database->getReference($this->tablename . '/' . $idUser . '/contracts/' . $contractID . '/ID')->set($contractID);

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

    

    public function list()
    {
        try {
            // Referência para a coleção de lucro no Firebase
            $ref = $this->database->getReference('profits');
            $profits = $ref->getValue(); // Obtém todas as lucro

            return $profits ? $profits : []; // Retorna as lucro ou um array vazio
        } catch (DatabaseException $e) {
            throw new \Exception("Erro ao listar lucros: " . $e->getMessage());
        }
    }

    public function update($id, array $data)
    {
        try {
            $ref = $this->database->getReference('expenses/' . $id); // Referência para a despesa específica
            $ref->update($data); // Atualiza os dados da lucro

            return $ref->getValue(); // Retorna a lucro atualizada
        } catch (DatabaseException $e) {
            throw new \Exception("Erro ao atualizar lucro: " . $e->getMessage());
        }
    }

   
    public function delete($id)
    {
        try {
            $ref = $this->database->getReference('expenses/' . $id); // Referência para a despesa específica
            $ref->remove(); // Deleta a lucro
        } catch (DatabaseException $e) {
            throw new \Exception("Erro ao deletar lucro: " . $e->getMessage());
        }
    }
}
