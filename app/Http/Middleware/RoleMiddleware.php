<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * 
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role  El rol requerido (ej: 'Admin')
     * @return Response
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!Auth::check()) {
            // retorna un error 401 si no está autenticado
            return response()->json(['message' => 'No autenticado'], 401);
        }

        $user = Auth::user();

        // Verificamos si el rol del usuario coincide con el requerido
        if ($user->rol !== $role) {
            // API: Retornar JSON 403 para arrojar un error de acceso denegado
            return response()->json([
                'message' => 'Acceso denegado. Se requiere rol: ' . $role
            ], 403);
        }

        return $next($request);
    }
}