<?php

namespace App\Services;
use Kreait\Firebase\Contract\Database;
use Kreait\Firebase\Exception\DatabaseException;

class ProfitsTypeService
{
    protected $database;
    protected $tablename;

    public function __construct(Database $database)
    {
        $this->database = $database;
        $this->tablename = "contacts"; // Nome da tabela no Firebase
    }

    public function addTypeProfits(array $data)
    {
        try {
            $idUser = $data['idUser'];
            $ref = $this->database->getReference($this->tablename.'/'.$idUser . '/type_profits');
            unset($data['idUser']);
            $newType = $ref->push($data);
            return [
                'status' => 'success',
                'ID' => $newType->getKey(),
                'type_profits' => $newType->getValue()
            ];
        } catch (DatabaseException $e) {
            throw new \Exception("Erro ao criar tipo de lucro: " . $e->getMessage());
        }
    }

    public function getAllTypes($idUser)
    {
       $ref = $this->database->getReference($this->tablename. '/' . $idUser . '/type_profits');
      
       $types = $ref->getValue();
       return $types ? $types : ['message' => 'Nenhum tipo de lucro encontrado.'];
    }

    public function updateType($id, $data)
    {
        try {
            $idUser = $data['idUser'];
            $ref = $this->database->getReference($this->tablename. '/' . $idUser . '/type_profits'. '/' . $id);
            unset($data['idUser']);
            $ref->update($data);
            return response()->json(['status' => 'success', 'message' => 'Tipo de lucro atualizado com sucesso.'], 200);
        } catch (DatabaseException $e) {
            throw new \Exception("Erro ao atualizar tipo de lucro: " . $e->getMessage());
        }
    }

   
    public function delete($id, $idUser)
    {
        $ref = $this->database->getReference($this->tablename. '/' . $idUser . '/type_profits/' . $id);
        if (!$ref->getSnapshot()->exists()) {
            return response()->json(['status' => 'error', 'message' => 'Tipo de lucro não encontrado.'], 404);
        }
        $ref->remove();

        return response()->json(['status' => 'success', 'message' => 'Tipo de lucro removido com sucesso.'], 200);
    }
}
