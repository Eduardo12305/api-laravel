<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CriptoRequest;
use App\Http\Requests\LoginRequest;
use App\Services\CriptoService;
use Illuminate\Http\Request;

class CriptoController extends Controller
{
    protected $criptoService;

    public function __construct(CriptoService $criptoService)
    {
        $this->criptoService = $criptoService;
    }

    public function addCripto(CriptoRequest $request)
    {

        $validated = $request->validated();
        $uid = $validated['uid'];
        $cripto = $validated['cripto'];
        return $this->criptoService->addCripto($uid, $cripto);
    }

    public function getCripto(CriptoRequest $request)
    {

        $validated = $request->validated();
        $uid = $validated['uid'];
        $cripto = $validated['idCripto'];
        return $this->criptoService->getCripto($uid, $cripto);
    }

    public function getAllCripto(CriptoRequest $request)
    {

        $validated = $request->validated();
        $uid = $validated['uid'];
        return $this->criptoService->getAllCripto($uid);
    }

    public function updCripto(CriptoRequest $request)
    {
        $validated = $request->validated();
        $uid = $validated['uid'];
        $cripto = $validated['idCripto'];
        $updCripito = $validated['cripto'];
        return $this->criptoService->updateCripto($uid, $cripto, $updCripito);
    }

    public function dltCripto(CriptoRequest $request)
    {
        $validated = $request->validated();
        $uid = $validated['uid'];
        $idCripto = $validated['idCripto'];
        return $this->criptoService->deleteCripto($uid, $idCripto);
    }

}
