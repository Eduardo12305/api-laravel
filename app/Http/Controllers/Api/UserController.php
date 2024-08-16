<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Services\UserService;
class UserController extends Controller
{

    // protected $database;

    // protected $tablename;

    // public function __construct(Database $database){
    //     $this->database = $database;
    //     $this->tablename = "contacts";
    // }

    // public function store(UserRequest $request)
    // {
        // $post = $this->database->getReference($this->tablename)->push($request->validated());   

        
        // return [
        //     "status" => "success",
        //     "message" => "Usuário criado com sucesso!",
        //     "data" => $request->validated(),
        // ];

    //     $register = $request->validated();
        
    //     if ($register['password'] !== $register['password_confirmation']) {
    //         return [
    //             'status' => 'error',
    //             'message' => 'As senhas não coincidem',
    //         ];
    //     }
    //     unset($register['password_confirmation']);

    //     $this->database->getReference($this->tablename)->push($register);

    //     return [
    //         'status' => 'success',
    //         'message' => 'usuario criado',
    //         'data' => $register,
    //     ];

    // }

    protected $userService;

    public function __construct(UserService $userService){
        $this->userService = $userService;
    }

    public function store(UserRequest $request) {
        // dd($request);
        $response = $this->userService->store($request);

        return response()->json($response);
    }

}
