<?php

namespace App\Http\Controllers\Security;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Carbon\Carbon;

class AuthSecurityController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Credenciales incorrectas',
            ], 401);
        }

        $user = Auth::user();

        if ($user->estado !== 'Activo') {
            return response()->json([
                'message' => 'Usuario inactivo o bloqueado',
            ], 403);
        }

        $now      = Carbon::now();
        $expires  = $now->copy()->addHour(); // 1 hora
        $payload  = [
            'iss' => config('app.url'),
            'iat' => $now->timestamp,
            'exp' => $expires->timestamp,
            'sub' => $user->id,
            'rol' => $user->rol,
        ];

        $token = JWT::encode($payload, env('JWT_SECRET'), 'HS256');

        return response()->json([
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'expires_in'   => $expires->diffInSeconds($now),
            'user'         => [
                'id'       => $user->id,
                'username' => $user->username,
                'rol'      => $user->rol,
            ],
        ]);
    }

    public function me(Request $request)
    {
        return response()->json([
            'user' => Auth::user(),
        ]);
    }

    public function logout(Request $request)
    {
        // Con JWT “puro” no hay invalidación en servidor
        return response()->json([
            'message' => 'Logout lógico realizado. (Solo borra el token en el cliente).',
        ]);
    }
}
