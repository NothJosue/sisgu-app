<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EstudianteController;

/*

 Rutas de Estudiante

 Prefijo automático: /api/estudiante
 Middleware automático: jwt, rol:Estudiante

*/

// Perfil y Académico
Route::get('/perfil', [EstudianteController::class, 'perfil']);
Route::get('/mis-matriculas', [EstudianteController::class, 'misMatriculas']);

// Proceso de Matrícula
Route::get('/matricula/cursos-disponibles', [EstudianteController::class, 'cursosDisponibles']);
Route::post('/matricula/registrar', [EstudianteController::class, 'registrarMatricula']);