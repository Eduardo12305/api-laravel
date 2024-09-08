<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Auth as FirebaseAuth;
use Kreait\Firebase\Exception\Auth\FailedToVerifyToken;

class FirebaseAuthController extends Controller
{
    protected $auth;

    public function __construct(FirebaseAuth $auth){
        $this->auth = $auth;
    }

    public function checkLogin(Request $request){
        // Pegar o token de autorização
        $authHeader = $request->header("Authorization");
        if(!$authHeader){
            return response()->json(['message' => 'Header de autorização não encontrado'], 400);
        }

        $idToken = str_replace('Bearer ','', $authHeader);

        try{
            // Verificar o token no firebase
            $verifiedIdToken = $this->auth->verifyIdToken($idToken);
            $uid = $verifiedIdToken->claims()->get('sub'); // Pega o Id do usuário pelo token

            // Pega o o objeto Firebase User
            $firebaseUser = $this->auth->getUser($uid);

            return response()->json([
                'message' => 'O usuário está logado',
                'user' => [
                    'uid' => $firebaseUser->uid,
                    'email' => $firebaseUser->email,
                    'displayName' => $firebaseUser->displayName,
                ]
                ], 200);
        } catch(FailedToVerifyToken $e){
            return response()->json(['message'=> 'Token expirado ou inválido'], 401);
        }

    }
}
