<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class JwtMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $authHeader = $request->header('Authorization');

        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            return response()->json(['message' => 'Token no enviado'], 401);
        }

        $token = substr($authHeader, 7);

        try {
            $decoded = JWT::decode(
                $token,
                new Key(env('JWT_SECRET'), 'HS256')
            );

            // Buscamos al usuario del token
            $user = User::find($decoded->sub);

            if (!$user || $user->estado !== 'Activo') {
                return response()->json(['message' => 'Token inválido o usuario inactivo'], 401);
            }

            // Inyectamos el usuario en el contexto de Auth
            Auth::setUser($user);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Token inválido',
                'error'   => $e->getMessage(),
            ], 401);
        }

        return $next($request);
    }
}
