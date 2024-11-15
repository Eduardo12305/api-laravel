<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ProfitsTypeService;
use App\Http\Requests\TypeProfitsRequest as type_profits;
use App\Http\Requests\IdRequest;
class ProfitsTypeController extends Controller
{
    protected $profitsTypeService;

    public function __construct(ProfitsTypeService $profitsTypeService)
    {
        $this->profitsTypeService = $profitsTypeService;
    }

    public function store(type_profits $request){
        $data = $request->all(); 
        $response = $this->profitsTypeService->addTypeProfits($data);
        return response()->json($response);
    }

    public function index($idUser){
       $response = $this->profitsTypeService->getAllTypes($idUser);
        return response()->json($response);
    }

    public function update(type_profits $request, $id){
        $data = $request->all();
        $response = $this->profitsTypeService->updateType($id, $data);
        return response()->json($response);
    }

    public function destroy(IdRequest $idRequest, $idUser ){
        $id = $idRequest['id'];
        $response = $this->profitsTypeService->delete($id, $idUser);
        return response()->json($response);
    }
}
