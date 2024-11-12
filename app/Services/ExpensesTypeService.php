<?php

namespace App\Services;

use Kreait\Firebase\Contract\Database;

class ExpensesTypeService
{
    protected $database;
    protected $tablename;
    protected $storage;

    public function __construct(Database $database)
    {
        $this->database = $database;
        $this->tablename = "tipos_gastos"; // Nome da tabela no Firebase
    }

    public function addTypes()
    {
        // Verifica se já existem tipos de gastos
        $existingTypesSnapshot = $this->database->getReference('tipos_gastos')->getSnapshot();

        if ($existingTypesSnapshot->exists()) {
            return response()->json(['status' => 'error', 'message' => 'Já existem tipos de gastos cadastrados.'], 400);
        }

        $types = [
            '0' => [
                'nome' => 'Alimentação',
                'descrição' => 'Despesas com alimentação',
                'cor' => '#FF0000'
            ],
            '1' => [
                'nome' => 'Transporte',
                'descrição' => 'Depesas com transporte',
                'cor' => '#00FF00'
            ],
            '2' => [
                'nome' => 'Moradia',
                'descrição' => 'Despesas com moradia',
                'cor' => '#0000FF'
            ],
            '3' => [
                'nome' => 'Educação',
                'icone' => 'Despesas com educação',
                'cor' => '#FFFF00'
            ],
            '4' => [
                'nome' => 'Saúde',
                'descrição' => 'Despesas com saúde',
                'cor' => '#00FFFF'
            ],
            '5' => [
                'nome' => 'Lazer',
                'descrição' => 'Despesas com lazer',
                'cor' => '#FF00FF'
            ],
            '6' => [
                'nome' => 'Outros',
                'descrição' => 'Despesas diversas',
                'cor' => '#000000'
            ],
        ];

        foreach ($types as $id => $type) {
            $this->database->getReference('tipos_gastos/' . $id)->set($type);
        }

        return response()->json(['status' => 'success', 'message' => 'Tipos de gastos cadastrados com sucesso.'], 200);
    }

    public function getTypeById($id)
    {
        $reference = $this->database->getReference('tipos_gastos/' . $id);
        $snapshot = $reference->getSnapshot();

        return $snapshot->exists() ? $snapshot->getValue() : null;
    }

    public function getAllTypes()
    {
        $reference = $this->database->getReference('tipos_gastos');
        $snapshot = $reference->getSnapshot();

        return $snapshot->exists() ? $snapshot->getValue() : null;
    }

    public function updateType($id, $data)
    {
        $reference = $this->database->getReference('tipos_gastos/' . $id);
        $reference->update($data);

        return response()->json(['status' => 'success', 'message' => 'Tipo de gasto atualizado com sucesso.'], 200);
    }

    public function deleteType($id)
    {
        $reference = $this->database->getReference('tipos_gastos/' . $id);
        $reference->remove();

        return response()->json(['status' => 'success', 'message' => 'Tipo de gasto removido com sucesso.'], 200);
    }
    
    public function changeUserType($id, $data)
    {
        $reference = $this->database->getReference('tipos_gastos/' . $id);
        $reference->update($data);

        return response()->json(['status' => 'success', 'message' => 'Tipo de gasto atualizado com sucesso.'], 200);
    }

    public function deleteAllTypes()
    {
        $reference = $this->database->getReference('tipos_gastos');
        $reference->remove();

        return response()->json(['status' => 'success', 'message' => 'Todos os tipos de gastos foram removidos com sucesso.'], 200);
    }
}