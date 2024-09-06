<?php

namespace App\Http\Middleware;

use Closure;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthUsers
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(UserRequest $request, Closure $next, $role): Response
    {
        $user = Auth::user();

        if($user && $user->role === $role) {
            return $next($request);
        }
        return response()->json(['message' => 'Erro de Autorização'], 403);
    }
}
