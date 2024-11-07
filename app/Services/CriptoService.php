<?php

namespace App\Services;

use Kreait\Firebase\Contract\Database;
use App\Services\PlanoService;


class CriptoService
{
    protected $database;
    protected $tablename;
    protected $planoService;

    public function __construct(Database $database, PlanoService $planoService)
    {
        $this->database = $database;
        $this->tablename = "contacts"; // Nome da tabela no Firebase
    }

    public function addCripto($uid, $cripto)
    {
        $userRef = $this->database->getReference($this->tablename . '/' . $uid)->getSnapshot();
        $criptoID = $this->database->getReference($this->tablename . '/' . $uid . '/criptos')->push();

        if ($userRef->exists()) {
            if (!array_key_exists('Valor', $cripto)) {
                $cripto['Valor'] = 0;
            }

            $criptoID->set($cripto);
            $criptoID = substr($criptoID, strrpos($criptoID, '/') + 1);
            $this->database->getReference($this->tablename . '/' . $uid . '/criptos/' . $criptoID . '/IDUser')->set($uid);
            $this->database->getReference($this->tablename . '/' . $uid . '/criptos/' . $criptoID . '/ID')->set($criptoID);
            return response()->json([
                'status' => 'success',
            ], 201);

        }

        return response()->json([
            'status' => 'error',
        ], 400);
    }

    public function getCripto($uid, $idCripto)
    {
        $userRef = $this->database->getReference($this->tablename . '/' . $uid)->getSnapshot();
        $criptoRef = $this->database->getReference($this->tablename . '/' . $uid . '/criptos/' . $idCripto)->getSnapshot();

        if ($userRef->exists() and $criptoRef->exists()) {

            return response()->json([
                'status' => 'success',
                'Cripto' => $criptoRef->getValue()
            ], 201);

        }

        return response()->json([
            'status' => 'error',
        ], 400);
    }

    public function getAllCripto($uid)
    {
        $userRef = $this->database->getReference($this->tablename . '/' . $uid)->getSnapshot();
        $criptoRef = $this->database->getReference($this->tablename . '/' . $uid . '/criptos/')->getSnapshot();

        if ($userRef->exists() and $criptoRef->exists()) {
            return response()->json([
                'status' => 'success',
                'Cripto' => $criptoRef->getValue()
            ], 201);

        }

        return response()->json([
            'status' => 'error',
        ], 400);
    }

    public function updateCripto($uid, $idCripto, $updCripto)
    {
        $userRef = $this->database->getReference($this->tablename . '/' . $uid)->getSnapshot();
        $criptoRef = $this->database->getReference($this->tablename . '/' . $uid . '/criptos/' . $idCripto)->getSnapshot();

        if ($userRef->exists() and $criptoRef->exists()) {

            $this->database->getReference($this->tablename . '/' . $uid . '/criptos/' . $idCripto)->update($updCripto);
            return response()->json([
                'status' => 'success',
            ], 201);

        }

        return response()->json([
            'status' => 'error',
        ], 400);
    }

    public function deleteCripto($uid, $idCripto)
    {
        $userRef = $this->database->getReference($this->tablename . '/' . $uid)->getSnapshot();
        $criptoRef = $this->database->getReference($this->tablename . '/' . $uid . '/criptos/' . $idCripto)->getSnapshot();

        if ($userRef->exists() and $criptoRef->exists()) {
            $this->database->getReference($this->tablename . '/' . $uid . '/criptos/' . $idCripto)->remove();
            return response()->json([
                'status' => 'success',
            ], 201);

        }

        return response()->json([
            'status' => 'error',
        ], 400);
    }

}