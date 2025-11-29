<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Estudiante;
use App\Models\Profesor;
use App\Models\Matricula;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalEstudiantes  = Estudiante::count();
        $totalProfesores   = Profesor::count();
        $matriculasHoy     = Matricula::whereDate('fecha_matricula', today())->count();
        $observados        = Matricula::where('estado', 'observado')->count();

        return view('dashboard', [
            'totalEstudiantes' => $totalEstudiantes,
            'totalProfesores'  => $totalProfesores,
            'matriculasHoy'    => $matriculasHoy,
            'observados'       => $observados,
        ]);
    }
}
