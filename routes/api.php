<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Security\AuthSecurityController;

/*


 Aquí configuramos los middlewares (seguridad) y cargamos los grupos de rutas.
 No definimos endpoints específicos aquí, solo la estructura.

*/

// 1. RUTAS PÚBLICAS
Route::post('/login', [AuthSecurityController::class, 'login']);

// 2. RUTAS PROTEGIDAS (JWT)
Route::middleware(['jwt'])->group(function () {
    
    // 2.1 Rutas Comunes (Perfil, Logout)
    Route::get('/me', [AuthSecurityController::class, 'me']);
    Route::post('/logout', [AuthSecurityController::class, 'logout']);

    // 2.2 rutas de admin
    // Cargamos las rutas de administrador
    Route::middleware('rol:Admin')
        ->prefix('admin')
        ->group(base_path('routes/api/admin.php'));

    // 2.3 rutas de estudiantes 
    // se carga las rutas de estudiante
    Route::middleware('rol:Estudiante')
        ->prefix('estudiante')
        ->group(base_path('routes/api/estudiante.php'));

    // 2.4 ZONA PROFESOR (Futuro)
    // Route::middleware('rol:Profesor')->prefix('profesor')->group(base_path('routes/api/profesor.php'));
});