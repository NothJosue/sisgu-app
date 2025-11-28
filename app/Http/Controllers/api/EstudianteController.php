<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Estudiante;

class EstudianteController extends Controller
{
    public function perfil()
    {
        $user = Auth::user();

        $estudiante = Estudiante::with([
            'carrera.escuela.facultad',
        ])->where('usuario_id', $user->id)->first();

        if (!$estudiante) {
            return response()->json(['message' => 'No se encontró perfil de estudiante'], 404);
        }

        return response()->json([
            'id'                  => $estudiante->id,
            'codigo_universitario'=> $estudiante->codigo_universitario,
            'nombres'             => $estudiante->nombres,
            'apellidos'           => $estudiante->apellidos,
            'dni'                 => $estudiante->dni,
            'estado'              => $estudiante->estado,
            'carrera'             => $estudiante->carrera->nombre ?? null,
            'escuela'             => $estudiante->carrera->escuela->nombre ?? null,
            'facultad'            => $estudiante->carrera->escuela->facultad->nombre ?? null,
        ]);
    }

    public function matriculas()
    {
        // De momento solo devolvemos vacío para que no rompa
        return response()->json([
            'matriculas' => [],
        ]);
    }
}
