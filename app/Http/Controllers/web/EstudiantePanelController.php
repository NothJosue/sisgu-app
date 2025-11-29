<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Estudiante;

class EstudiantePanelController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        $estudiante = Estudiante::with('carrera.escuela.facultad')
            ->where('usuario_id', $user->id)
            ->firstOrFail();

        return view('estudiante.dashboard', compact('estudiante'));
    }
}
