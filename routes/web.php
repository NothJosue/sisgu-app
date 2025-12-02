<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
// Controlador General (Admin)
use App\Http\Controllers\MatriculaController; 
// Controlador de Estudiante (El que creamos para los pagos) - Usamos alias para diferenciar
use App\Http\Controllers\Estudiante\MatriculaController as EstudianteMatriculaController;

use App\Models\User;
use App\Models\Estudiante;
use App\Models\Profesor;

// ==========================================
// 1. AUTENTICACIÓN Y ACCESO PÚBLICO
// ==========================================

Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ==========================================
// 2. RUTAS PROTEGIDAS (REQUIEREN LOGIN)
// ==========================================

Route::middleware('auth')->group(function () {

    // --------------------------------------
    // MÓDULO ADMINISTRADOR
    // --------------------------------------
    Route::prefix('admin')->middleware('rol:Admin')->group(function () {

        // Dashboard Principal
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('admin.dashboard');

        // Gestión de Matrículas (Admin)
        Route::get('/matriculas', [MatriculaController::class, 'index'])->name('admin.matriculas.index');
        Route::post('/matriculas', [MatriculaController::class, 'store'])->name('admin.matriculas.store');
    });

    // --------------------------------------
    // MÓDULO ESTUDIANTE
    // --------------------------------------
    Route::prefix('estudiante')->middleware('rol:Estudiante')->group(function () {

        Route::get('/dashboard', function () {
            $estudiante = Estudiante::with('carrera.escuela.facultad')
                ->where('usuario_id', Auth::id())
                ->first();

            // Validación por si el usuario existe pero no tiene perfil de estudiante creado
            if (!$estudiante) {
                return "Error: Tu usuario es 'Estudiante' pero no tienes perfil creado en la tabla 'estudiantes'.";
            }

            return view('estudiante.dashboard', compact('estudiante'));
        })->name('estudiante.dashboard');

        // RUTAS DE TRÁMITES
        Route::prefix('matricula')->group(function () {

            // 1. Matrícula Regular (CONECTADO AL CONTROLADOR NUEVO)
            Route::get('/regular', [EstudianteMatriculaController::class, 'create'])->name('matricula.regular.create');
            Route::post('/regular', [EstudianteMatriculaController::class, 'store'])->name('matricula.regular.store');

            // 2. Reserva de Matrícula
            Route::get('/reserva', function () {
                return view('estudiante.matricula.reserva');
            })->name('estudiante.matricula.reserva');

            // 3. Ampliación de Créditos
            Route::get('/ampliacion', function () {
                return view('estudiante.matricula.ampliacion');
            })->name('estudiante.matricula.ampliacion');

            // 4. Reactualización
            Route::get('/reactualizacion', function () {
                return view('estudiante.matricula.reactualizacion');
            })->name('estudiante.matricula.reactualizacion');

            // 5. Rectificación
            Route::get('/rectificacion', function () {
                return view('estudiante.matricula.rectificacion');
            })->name('estudiante.matricula.rectificacion');

            // 6. Retiro
            Route::get('/retiro', function () {
                return view('estudiante.matricula.retiro');
            })->name('estudiante.matricula.retiro');
        });
    });

    // --------------------------------------
    // MÓDULO PROFESOR
    // --------------------------------------
    Route::prefix('profesor')->middleware('rol:Profesor')->group(function () {

        Route::get('/dashboard', function () {
            $profesor = Profesor::where('usuario_id', Auth::id())->first();

            if (!$profesor) {
                return "Error: Tu usuario es 'Profesor' pero no tienes perfil creado en la tabla 'profesores'.";
            }

            return view('profesor.dashboard', compact('profesor'));
        })->name('profesor.dashboard');
    });

});

// =========================================================================
// 3. 🚧 RUTA DE DESARROLLO (BORRAR EN PRODUCCIÓN) 🚧
// =========================================================================
Route::get('/entrar-como/{id}', function ($id) {
    $user = User::find($id);

    if (!$user) {
        return "El usuario con ID $id no existe.";
    }

    Auth::login($user);

    switch ($user->rol) {
        case 'Admin':
            return redirect()->route('admin.dashboard');
        case 'Estudiante':
            return redirect()->route('estudiante.dashboard');
        case 'Profesor':
            return redirect()->route('profesor.dashboard');
        default:
            return redirect('/');
    }
});