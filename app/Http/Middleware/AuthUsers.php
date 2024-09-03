<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request; // Use o namespace correto para a requisição padrão
use App\Http\Requests\UserRequest; // Certifique-se de usar o namespace correto
use Symfony\Component\HttpFoundation\Response;

class AuthUsers
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request|\App\Http\Requests\UserRequest  $request
     * @param  \Closure  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Convert to UserRequest if possible
        if ($request instanceof UserRequest) {
            $userRequest = $request;
        } else {
            // If it's not a UserRequest, handle it as a standard Request
            $userRequest = new UserRequest($request->all());
        }

        // Verifica se a rota é a de criação de conta e permite acesso sem autenticação
        if ($userRequest->is('register')) {
            return $next($request);
        }

        $user = $request->user(); // Assume que o usuário está autenticado e disponível na solicitação

        if (!$user) {
            return response()->json(['message' => 'Não autenticado.'], 401);
        }

        // Verifica o papel do usuário e permite o acesso às rotas com base no papel
        $route = $request->route()->getName(); // Obtém o nome da rota atual

        switch ($user->role) {
            case 'superadmin':
                // Superadmin pode acessar todas as rotas
                return $next($request);

            case 'admin':
                // Admin pode acessar rotas específicas
                if (in_array($route, ['rota.admin', 'rota.cliente'])) {
                    return $next($request);
                }
                return response()->json(['message' => 'Acesso negado.'], 403);

            case 'cliente':
                // Cliente pode acessar rotas específicas
                if ($route === 'rota.cliente') {
                    return $next($request);
                }
                return response()->json(['message' => 'Acesso negado.'], 403);

            default:
                return response()->json(['message' => 'Papel do usuário desconhecido.'], 403);
        }
    }
}
