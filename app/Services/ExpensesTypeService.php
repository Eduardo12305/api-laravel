<?php

namespace App\Services;

use Kreait\Firebase\Contract\Database;
use Kreait\Firebase\Exception\DatabaseException;
class ExpensesTypeService
{
    protected $database;
    protected $tablename;
    protected $storage;

    public function __construct(Database $database)
    {
        $this->database = $database;
        $this->tablename = "contacts"; // Nome da tabela no Firebase
    }

    public function addType(array $data)
    {
        try {
            $idUser = $data['idUser'];
            $ref = $this->database->getReference($this->tablename.'/'.$idUser . '/type_expenses');
            unset($data['idUser']);
            $newType = $ref->push($data);
            return [
                'status' => 'success',
                'ID' => $newType->getKey(),
                'type_expenses' => $newType->getValue()
            ];
        } catch (DatabaseException $e) {
            throw new \Exception("Erro ao criar tipo de gasto: " . $e->getMessage());
        }
    }

    public function getAllTypes($idUser)
    {
        $ref = $this->database->getReference($this->tablename.'/'.$idUser . '/type_expenses');
        $types = $ref->getValue();

        return $types ? $types : ['message' => 'Nenhum tipo de gasto encontrado.'];
    }

    public function updateType($id, $data)
    {
        try {
            $idUser = $data['idUser'];
            $reference = $this->database->getReference($this->tablename. '/' . $idUser . '/type_expenses/' . $id);
            unset($data['idUser']);
            $reference->update($data);
            return response()->json(['status' => 'success', 'message' => 'Tipo de gasto atualizado com sucesso.'], 200);
        } catch (DatabaseException $e) {
            throw new \Exception("Erro ao atualizar tipo de gasto: " . $e->getMessage());
        }
        
    }

    public function deleteType($id, $idUser)
    {
        $reference = $this->database->getReference($this->tablename.'/'.$idUser . '/type_expenses');
        if (!$reference->getSnapshot()->exists()) {
            return response()->json(['status' => 'error', 'message' => 'Tipo de gasto não encontrado.'], 404);
        }
        $reference->remove();

        return response()->json(['status' => 'success', 'message' => 'Tipo de gasto removido com sucesso.'], 200);
    }
}