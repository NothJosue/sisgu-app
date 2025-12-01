<?php

namespace App\Http\Controllers\web\Horarios;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Horarios;
use App\Models\PeriodoAcademico;
use App\Models\AsignaturaSeccion;
use App\Models\Aula;
use App\Models\BloquesHorarios;

class HorarioController extends Controller
{
    /**
     * Muestra el listado general de horarios programados.
     */
    public function index(Request $request)
    {
        // Filtro por periodo (por defecto el último)
        $periodoId = $request->get('periodo_id');
        $periodoActual = $periodoId ? PeriodoAcademico::find($periodoId) : PeriodoAcademico::latest('id')->first();
        $periodos = PeriodoAcademico::orderBy('id', 'desc')->get();

        $horarios = Horarios::with([
                'seccion.asignatura', 
                'seccion.profesor', 
                'aula.edificio', 
                'bloque'
            ])
            ->where('periodo_id', $periodoActual->id)
            ->orderBy('dia_id')
            ->orderBy('bloque_id')
            ->paginate(20);

        return view('admin.horarios.index', compact('horarios', 'periodos', 'periodoActual'));
    }

    /**
     * Asigna un horario a una sección existente.
     */
    public function store(Request $request)
    {
        $request->validate([
            'asignatura_seccion_id' => 'required|exists:asignatura_seccions,id',
            'dia_id' => 'required|integer|min:1|max:7',
            'bloque_id' => 'required|exists:bloques_horarios,id',
            'aula_id' => 'required|exists:aulas,id',
            'periodo_id' => 'required|exists:periodo_academicos,id',
        ]);

        // Validación de cruce de horarios (Aula ocupada)
        $cruce = Horarios::where('aula_id', $request->aula_id)
            ->where('dia_id', $request->dia_id)
            ->where('bloque_id', $request->bloque_id)
            ->where('periodo_id', $request->periodo_id)
            ->exists();

        if ($cruce) {
            return back()->withErrors(['aula_id' => 'El aula ya está ocupada en ese horario.']);
        }

        Horarios::create($request->all());

        return redirect()->route('admin.horarios.index')
                ->with('success', 'Horario asignado correctamente.');
    }
}