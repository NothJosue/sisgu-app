<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Estudiante;
use App\Models\Matricula;
use App\Models\PeriodoAcademico;
use App\Models\Pago;

class AdminMatriculaController extends Controller
{
    public function index()
    {
        $estudiantes = Estudiante::orderBy('apellidos')->get();

        $matriculas = Matricula::with([
                'estudiante.carrera.escuela',
                'periodo',
                'pago'
            ])
            ->orderByDesc('fecha_matricula')
            ->get();

        return view('matricula.index', compact('estudiantes', 'matriculas'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'estudiante_id'    => 'required|exists:estudiantes,id',
            'periodo_id'       => 'required|exists:periodo_academicos,id',
            'codigo_matricula' => 'nullable|string|max:30',
            'id_tramite'       => 'nullable|integer',
            'fecha_matricula'  => 'required|date',
            'estado'           => 'required|string',
        ]);

        // Si quisieras enlazar con un pago real, aquí buscarías el Pago por id_tramite o similar.
        $pago = null;

        $matricula = Matricula::create([
            'estudiante_id'    => $data['estudiante_id'],
            'periodo_id'       => $data['periodo_id'],
            'pago_id'          => $pago?->id,
            'codigo_matricula' => $data['codigo_matricula'] ?: ('MAT-' . now()->format('Ymd-His')),
            'id_tramite'       => $data['id_tramite'],
            'fecha_matricula'  => $data['fecha_matricula'],
            'estado'           => strtolower($data['estado']), // guarda en minúsculas si quieres
        ]);

        return redirect()
            ->route('admin.matriculas.index')
            ->with('success', 'Matrícula registrada correctamente');
    }
}
