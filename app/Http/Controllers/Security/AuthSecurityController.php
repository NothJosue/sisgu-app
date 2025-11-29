<?php

namespace App\Http\Controllers\Security;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Firebase\JWT\JWT;
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
            return response()->json(['message' => 'Credenciales incorrectas'], 401);
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->estado !== 'Activo') {
            return response()->json(['message' => 'Usuario inactivo o bloqueado'], 403);
        }

        // Configuración de Tiempos
        $now = Carbon::now();
        $expirationTime = $now->copy()->addHours(2); // Token de 2 horas
        $seconds = $expirationTime->diffInSeconds($now); // Cálculo absoluto

        $payload = [
            'iss' => config('app.url'),
            'iat' => $now->timestamp,
            'exp' => $expirationTime->timestamp,
            'sub' => $user->id,
            'rol' => $user->rol,
        ];

        $key = env('JWT_SECRET');
        if(!$key) {
             return response()->json(['message' => 'Error de configuración del servidor (Falta JWT_SECRET)'], 500);
        }

        $token = JWT::encode($payload, $key, 'HS256');

        return response()->json([
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'expires_in'   => $seconds, // Ahora será positivo (ej: 7200)
            'user'         => [
                'id'       => $user->id,
                'username' => $user->username,
                'rol'      => $user->rol,
                'estado'   => $user->estado
            ],
        ]);
    }

    public function me(Request $request)
    {
        // Devuelve el usuario con sus relaciones principales si es estudiante
        $user = Auth::user();
        
        if ($user->rol === 'Estudiante') {
             // Cargamos datos del perfil de estudiante si existen
             $perfil = \App\Models\Estudiante::where('usuario_id', $user->id)
                        ->with('carrera.escuela.facultad')
                        ->first();
             $user->perfil = $perfil;
        }

        return response()->json(['user' => $user]);
    }

    public function logout(Request $request)
    {
        return response()->json(['message' => 'Logout exitoso (Cliente debe borrar token)']);
    }
}