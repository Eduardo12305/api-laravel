<?php

namespace App\Services;

use Kreait\Firebase\Contract\Database;

class PlanoService {
    protected $database;
    protected $tablename;
    protected $storage;

    public function __construct(Database $database)
    {
        $this->database = $database;
        $this->tablename = "planos"; // Nome da tabela no Firebase

    }
    public function addPlanos()
{
    // Verifica se já existem planos
    $existingPlansSnapshot = $this->database->getReference('planos')->getSnapshot();

    if ($existingPlansSnapshot->exists()) {
        return response()->json(['status' => 'error', 'message' => 'Já existem planos cadastrados.'], 400);
    }

    $plans = [
        '0' => [
            'nome' => 'Plano Gratuito',
            'valor' => 0,
            'qt_tipos_gastos' => 5,
            'intervalo_cambio_moedas' => '1 hora',
            'intervalo_cambio_criptomoedas' => '1 hora',
            'previsao_renda' => 6,
            'graficos_avancados' => false
        ],
        '1' => [
            'nome' => 'Plano Básico',
            'valor' => 29.90,
            'qt_tipos_gastos' => 10,
            'intervalo_cambio_moedas' => '30 minutos',
            'intervalo_cambio_criptomoedas' => '30 minutos',
            'previsao_renda' => 12,
            'graficos_avancados' => true
        ],
        '2' => [
            'nome' => 'Plano Premium',
            'valor' => 49.90,
            'qt_tipos_gastos' => 20,
            'intervalo_cambio_moedas' => '15 minutos',
            'intervalo_cambio_criptomoedas' => '15 minutos',
            'previsao_renda' => 24,
            'graficos_avancados' => true
        ],
    ];

    foreach ($plans as $id => $plan) {
        $this->database->getReference('planos/' . $id)->set($plan);
    }

    return response()->json(['status' => 'success', 'message' => 'Planos adicionados com sucesso!']);
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
            // $key é o ID do plano (a chave no Firebase)
            // $value é o valor associado a essa chave (os dados do plano)
            
            if (is_array($value)) {
                $formattedPlans[$key] = array_merge(['id' => $key], $value);
            } else {
                $formattedPlans[$key] = ['id' => $key];
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

}
