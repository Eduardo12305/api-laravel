<?php

namespace App\Services;

use Kreait\Firebase\Contract\Database;
use Kreait\Firebase\Exception\DatabaseException;

class PlanoService {
    protected $database;
    protected $tablename;
    protected $storage;

    public function __construct(Database $database)
    {
        $this->database = $database;
        $this->tablename = "planos"; // Nome da tabela no Firebase

    }
    public function addPlano(array $data)
{
    try{
        $ref = $this->database->getReference($this->tablename . '/');
        $newPlan = $ref->push($data);
        return [
            'status' => 'success',
            'ID' => $newPlan->getKey(),
            'plan' => $newPlan->getValue()
        ];
    }catch (DatabaseException $e) {
        throw new \Exception("Erro ao criar plano: " . $e->getMessage());
    }
}


    public function getPlanById($id)
    {
        $reference = $this->database->getReference('planos/' . $id);
        $snapshot = $reference->getSnapshot();

        return $snapshot->exists() ? $snapshot->getValue() : null;
    }

    public function calculateDueDate($planId)
    {
        if($planId ==0){
            return null;
        }

        $currentDate = now();
        return $currentDate->addMonths(0);
    }

    public function getAllPlans()
{
    // Obtém a referência ao nó 'planos'
    $reference = $this->database->getReference('planos');
    $snapshot = $reference->getSnapshot();

    // Verifica se o snapshot existe
    if (!$snapshot->exists()) {
        return []; // Retorna um array vazio se não houver planos
    }

    // Obtém todos os planos
    $plans = $snapshot->getValue(); // $plans é um array de planos

    // Formata os dados para incluir os IDs
    $formattedPlans = [];
    foreach ($plans as $key => $value) {
        // Certifique-se de que $value é um array
        if (is_array($value)) {
            $formattedPlans[$key] = array_merge(['id' => $key], $value);
        } else {
            // Se não for um array, crie uma estrutura padrão
            $formattedPlans[$key] = ['id' => $key, 'data' => $value];
        }
    }

    return $formattedPlans; // Retorna os planos formatados
}


    public function deleteAllPlans()
    {
        // Obtém a referência ao nó 'planos'
        $reference = $this->database->getReference('planos');

        // Remove todos os dados do nó 'planos'
        $reference->remove();

        return [
            'status' => 'success',
            'message' => 'Todos os planos foram excluídos com sucesso.',
        ];
    }

    public function changePlan($planId, array $data) {
        // Verifica se o plano existe
        $planReference = $this->database->getReference('planos'.'/'. $planId);
        $planSnapshot = $planReference->getSnapshot();
    
        if (!$planSnapshot->exists()) {
            return [
                'status' => 'error',
                'message' => 'O plano informado não existe.',
            ];
        }
        $planReference->update($data);
    
        return [
            'status' => 'success',
            'message' => 'Plano do usuário alterado com sucesso.',
        ];
    }

    public function changePlanUser($idUser, $plano_update){
        // dd($idUser, $plano_id, $plano_update);
        $this->database->getReference('contacts' . '/' . $idUser)->update(['id_plano' => $plano_update]);
        return [
            'status' => 'success',
            'message' => 'Plano do usuário alterado com sucesso.',
            
        ];
        // dd($userReference);
    }
}
