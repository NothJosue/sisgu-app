<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Estudiante;
use App\Models\Profesor;

class AdminController extends Controller
{
    public function listarEstudiantes()
    {
        $estudiantes = Estudiante::with('carrera')->get();

        return response()->json($estudiantes);
    }

    public function listarProfesores()
    {
        $profesores = Profesor::with('usuario')->get();

        return response()->json($profesores);
    }
}
