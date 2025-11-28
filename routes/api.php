<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Security\AuthSecurityController;
use App\Http\Controllers\Api\EstudianteController;
use App\Http\Controllers\Api\AdminController;

// LOGIN (sin middleware jwt)
Route::post('/login', [AuthSecurityController::class, 'login']);

// Rutas protegidas por JWT
Route::middleware('jwt')->group(function () {

    Route::get('/me', [AuthSecurityController::class, 'me']);
    Route::post('/logout', [AuthSecurityController::class, 'logout']);

    // ==== RUTAS DE ADMIN ====
    Route::middleware('rol:Admin')->group(function () {
        Route::get('/admin/estudiantes', [AdminController::class, 'listarEstudiantes']);
        Route::get('/admin/profesores', [AdminController::class, 'listarProfesores']);
    });

    // ==== RUTAS DE ESTUDIANTE ====
    Route::middleware('rol:Estudiante')->group(function () {
        Route::get('/estudiante/perfil', [EstudianteController::class, 'perfil']);
        Route::get('/estudiante/matriculas', [EstudianteController::class, 'matriculas']);
    });
});
