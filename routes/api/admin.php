<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AdminController;

/*

    Rutas para el Administrador

    Prefijo automático: /api/admin
    Middleware automático: jwt, rol:Admin

*/

// Dashboard
Route::get('/dashboard', [AdminController::class, 'dashboardData']);

// Gestión de Estudiantes
Route::get('/estudiantes', [AdminController::class, 'listarEstudiantes']);
Route::post('/estudiantes', [AdminController::class, 'storeEstudiante']); // Crear

// Gestión de Profesores
Route::get('/profesores', [AdminController::class, 'listarProfesores']);
Route::post('/profesores', [AdminController::class, 'storeProfesor']); // Crear