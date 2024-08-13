<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Database;
use Kreait\Laravel\Firebase\Facades\Firebase;
class UserController extends Controller
{

    protected $database;

    protected $tablename;

    public function __construct(Database $database){
        $this->database = $database;
        $this->tablename = "contacts";
    }

    public function register(UserRequest $request)
    {
        $post = $this->database->getReference($this->tablename)->push($request->validated());   

        return [
            "status" => "success",
            "message" => "Usuário criado com sucesso!",
            "data" => $request->validated(),
        ];
    }

}
