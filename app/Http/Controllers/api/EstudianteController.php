<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Estudiante;
use App\Models\Matricula;
use App\Services\MatriculaService;

class EstudianteController extends Controller
{
    protected $matriculaService;

    public function __construct(MatriculaService $matriculaService)
    {
        $this->matriculaService = $matriculaService;
    }

    // Ver perfil completo
    public function perfil()
    {
        $user = Auth::user();
        $estudiante = Estudiante::with(['carrera.escuela.facultad', 'detalle']) // Asegúrate de tener relación 'detalle'
                        ->where('usuario_id', $user->id)
                        ->firstOrFail();

        return response()->json($estudiante);
    }

    // Ver cursos matriculados actuales
    public function misMatriculas()
    {
        $user = Auth::user();
        $estudiante = Estudiante::where('usuario_id', $user->id)->firstOrFail();

        $matriculas = Matricula::where('estudiante_id', $estudiante->id)
                        ->with(['seccion.asignatura', 'seccion.horarios', 'seccion.profesor'])
                        ->get();

        return response()->json($matriculas);
    }

    // 1. Obtener cursos disponibles para matricularse
    public function cursosDisponibles()
    {
        $user = Auth::user();
        $estudiante = Estudiante::where('usuario_id', $user->id)->firstOrFail();

        try {
            $data = $this->matriculaService->obtenerCursosDisponibles($estudiante->id);
            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    // 2. Procesar la matrícula
    public function registrarMatricula(Request $request)
    {
        $request->validate([
            'secciones_ids' => 'required|array|min:1',
            'secciones_ids.*' => 'exists:asignatura_seccions,id'
        ]);

        $user = Auth::user();
        $estudiante = Estudiante::where('usuario_id', $user->id)->firstOrFail();

        try {
            $matriculas = $this->matriculaService->registrarMatricula(
                $estudiante->id, 
                $request->secciones_ids
            );
            return response()->json(['message' => 'Matrícula exitosa', 'data' => $matriculas]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al matricular: ' . $e->getMessage()], 500);
        }
    }
}