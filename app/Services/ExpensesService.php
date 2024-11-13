<?php

namespace App\Services;
use Kreait\Firebase\Contract\Database;
use Kreait\Firebase\Exception\DatabaseException;

class ExpensesService
{
    protected $database;
    protected $tablename;

    public function __construct(Database $database)
    {
        $this->database = $database;
        $this->tablename = "contacts"; // Nome da tabela no Firebase
    }

    public function createExpense(array $data)
    {
        try {
            // Adiciona a data de início automaticamente com a data atual
            $data['dt_init'] = now(); // Preenche automaticamente a data de início com a data e hora atuais
            $data['dt_update'] = now(); // Preenche automaticamente a data de atualização com a data e hora atuais
            $idUser = $data['idUser']; // Obtém o ID do usuário
            // Referência para a coleção de despesas no Firebase
            $ref = $this->database->getReference($this->tablename . '/' . $idUser. '/expenses');
            $newExpense = $ref->push($data); // Adiciona a despesa na coleção

            return $newExpense->getValue(); // Retorna a despesa criada
        } catch (DatabaseException $e) {
            throw new \Exception("Erro ao criar despesa: " . $e->getMessage());
        }
    }

    public function list($idUser)
    {
        try {
            // Referência para a coleção de despesas no Firebase
            $ref = $this->database->getReference($this->tablename . '/' . $idUser . '/expenses');
            $expenses = $ref->getValue(); // Obtém todas as despesas

            return $expenses ? $expenses : []; // Retorna as despesas ou um array vazio
        } catch (DatabaseException $e) {
            throw new \Exception("Erro ao listar despesas: " . $e->getMessage());
        }
    }

    public function update($id, array $data)
    {
        try {
            $idUser = $data['idUser']; // Obtém o ID do usuário
            $ref = $this->database->getReference($this->tablename . '/' . $idUser . '/expenses/' . $id); // Referência para a despesa específica
            $ref->update($data); // Atualiza os dados da despesa

            return $ref->getValue(); // Retorna a despesa atualizada
        } catch (DatabaseException $e) {
            throw new \Exception("Erro ao atualizar despesa: " . $e->getMessage());
        }
    }

   
    public function delete(array $idDelete,$id)
    {
        try {
            $idUser = $idDelete['idUser']; // Obtém o ID do usuário
            $ref = $this->database->getReference($this->tablename.'/'.$idUser.'/expenses/' . $id); // Referência para a despesa específica
            $ref->remove(); // Deleta a despesa
        } catch (DatabaseException $e) {
            throw new \Exception("Erro ao deletar despesa: " . $e->getMessage());
        }
    }
}
