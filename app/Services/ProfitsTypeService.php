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
        $this->tablename = "profits"; // Nome da tabela no Firebase
    }

    public function addTypeProfits()
    {
        $existenteProfitsSnapshot = $this->database->getReference('profits')->getSnapshot();

        if($existenteProfitsSnapshot->exists()){
            return response()->json(['status' => 'error', 'message' => 'Já existem tipos de lucros cadastrados.'], 400);
        }

        $types = [
            '0' => [
                'name' => 'Salário',
                'description' => 'Lucro proveniente de salário',
                'cor' => '#FF0000'
            ],
            '1' => [
                'name' => 'Investimento',
                'description' => 'Lucro proveniente de investimento',
                'cor' => '#00FF00'
            ],
            '2' => [
                'name' => 'Vendas',
                'description' => 'Lucro proveniente de vendas',
                'cor' => '#0000FF'
            ],
            '3' => [
                'name' => 'Outros',
                'description' => 'Outros tipos de lucro',
                'cor' => '#FFFF00'
            ]
        ];

        foreach ($types as $key => $type) {
            $this->database->getReference('profits/' . $key)->set($type);
        }

        return response()->json(['status' => 'success', 'message' => 'Tipos de lucros cadastrados com sucesso.'], 201);
    }

    public function getTypeById($id)
    {
        $refence = $this->database->getReference('profits'. $id);
        $snapshot = $refence->getSnapshot();

        return $snapshot->exists() ? $snapshot->getValue() : null;
    }

    public function getAllTypes()
    {
       $reference = $this->database->getReference('profits');
       $snapshot = $reference->getSnapshot();
       return $snapshot->exists() ? $snapshot->getValue() : null;
    }

    public function updateType($id, $data)
    {
        try {
            $ref = $this->database->getReference('profits/' . $id);
            $ref->update($data);
        } catch (DatabaseException $e) {
            throw new \Exception("Erro ao atualizar tipo de lucro: " . $e->getMessage());
        }
    }

   
    public function delete($id)
    {
        $reference = $this->database->getReference('profits/' . $id);
        $reference->remove();

        return response()->json(['status' => 'success', 'message' => 'Tipo de lucro removido com sucesso.'], 200);
    }
}
