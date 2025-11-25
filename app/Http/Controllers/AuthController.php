<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Muestra la vista del Login (GET)
     */
    public function showLogin()
    {
        // Si el usuario ya está logueado, lo mandamos directo a su dashboard
        // para que no vea el login de nuevo.
        if (Auth::check()) {
            return $this->redirectPorRol(Auth::user());
        }

        return view('login');
    }

    /**
     * Procesa los datos del formulario (POST)
     */
    public function login(Request $request) 
    {
        // 1. Validar datos
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required'
        ]);

        // 2. Intentar loguear
        // Laravel compara 'username' y encripta 'password' automáticamente
        if (Auth::attempt($credentials)) {
            
            // Seguridad: Regenerar ID de sesión para evitar ataques de fijación
            $request->session()->regenerate();

            $user = Auth::user();

            // 3. Verificación de estado (Bloqueo de usuarios inactivos)
            if($user->estado !== 'Activo') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                
                return back()->withErrors([
                    'username' => 'Tu cuenta está inactiva o suspendida.'
                ]);
            }

            // 4. Redirección según Rol
            return $this->redirectPorRol($user);
        }

        // 5. Si falla el login
        return back()->withErrors([
            'username' => 'Credenciales incorrectas.',
        ])->onlyInput('username');
    }

    /**
     * Cierra la sesión del usuario (POST)
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /**
     * Función auxiliar para redirigir según el rol.
     * La separé para poder reusarla si es necesario.
     */
    private function redirectPorRol($user)
    {
        switch ($user->rol) {
            case 'Admin':
                return redirect()->route('admin.dashboard');
            case 'Estudiante':
                return redirect()->route('estudiante.dashboard');
            case 'Profesor':
                return redirect()->route('profesor.dashboard');
            case 'Soporte': // Agregué soporte por si acaso lo usas luego
                 // return redirect()->route('soporte.dashboard');
                 return redirect('/'); 
            default:
                Auth::logout(); // Si tiene un rol raro, lo sacamos
                return redirect('/')->withErrors(['username' => 'Rol no reconocido.']);
        }
    }
}