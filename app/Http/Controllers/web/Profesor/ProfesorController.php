<?php

namespace App\Http\Controllers\web\Profesor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\AsignaturaSeccion;
use App\Models\Profesor;

class ProfesorController extends Controller
{
    public function misCarreras()
    {
        $user = Auth::user();
        $profesor = Profesor::where('usuario_id', $user->id)->firstOrFail();

        $carreras = DB::table('asignatura_seccions as sec')
            ->join('malla_curricular as malla', 'sec.asignatura_id', '=', 'malla.asignatura_id')
            ->join('carreras as car', 'malla.carrera_id', '=', 'car.id')
            ->join('escuelas as esc', 'car.escuela_id', '=', 'esc.id')
            ->join('facultades as fac', 'esc.facultad_id', '=', 'fac.id')
            ->where('sec.profesor_id', $profesor->id)
            ->select(
                'car.id as carrera_id', 
                'car.nombre as carrera_nombre',
                'esc.nombre as escuela_nombre',
                'fac.nombre as facultad_nombre'
            )
            ->distinct()
            ->get();

        return view('profesor.carreras', compact('carreras', 'profesor'));
    }

    public function misAsignaturas()
    {
        $user = Auth::user();
        $profesor = Profesor::where('usuario_id', $user->id)->firstOrFail();

        $asignaturas = AsignaturaSeccion::where('profesor_id', $profesor->id)
            ->with(['asignatura', 'periodo'])
            ->get()
            ->map(function ($seccion) {
                return (object) [
                    'codigo' => $seccion->asignatura->codigo_asignatura,
                    'nombre' => $seccion->asignatura->nombre,
                    'creditos' => $seccion->asignatura->creditos,
                    'seccion' => $seccion->nombre_seccion,
                    'periodo' => $seccion->periodo->codigo,
                    'modalidad' => $seccion->modalidad
                ];
            });

        return view('profesor.asignaturas', compact('asignaturas'));
    }

    public function miHorario()
    {
        $user = Auth::user();
        $profesor = Profesor::where('usuario_id', $user->id)->firstOrFail();

        $horario = AsignaturaSeccion::where('profesor_id', $profesor->id)
            ->with(['asignatura', 'horarios.bloque', 'horarios.aula'])
            ->get()
            ->flatMap(function ($seccion) {
                return $seccion->horarios->map(function ($h) use ($seccion) {
                    return (object) [
                        'dia' => $this->getDiaNombre($h->dia_id),
                        'dia_id' => $h->dia_id,
                        'hora_inicio' => $h->bloque->hora_inicio,
                        'hora_fin' => $h->bloque->hora_fin,
                        'curso' => $seccion->asignatura->nombre,
                        'aula' => $h->aula->nombre_aula,
                        'seccion' => $seccion->nombre_seccion
                    ];
                });
            })
            ->sortBy(['dia_id', 'hora_inicio']);

        return view('profesor.horario', compact('horario'));
    }

    private function getDiaNombre($id) {
        $dias = [1 => 'Lunes', 2 => 'Martes', 3 => 'Miércoles', 4 => 'Jueves', 5 => 'Viernes', 6 => 'Sábado'];
        return $dias[$id] ?? 'N/A';
    }
}