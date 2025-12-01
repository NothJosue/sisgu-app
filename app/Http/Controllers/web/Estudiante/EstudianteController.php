<?php

namespace App\Http\Controllers\web\Estudiante;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Estudiante;
use App\Models\Matricula;
use App\Models\DetalleMatricula;
use App\Models\MallaCurricular;
use App\Models\PeriodoAcademico;
use App\Services\MatriculaService;

class EstudianteController extends Controller
{
    protected $matriculaService;

    public function __construct(MatriculaService $matriculaService)
    {
        $this->matriculaService = $matriculaService;
    }

    public function historial()
    {
        $user = Auth::user();
        $estudiante = Estudiante::with(['carrera', 'detalle'])->where('usuario_id', $user->id)->firstOrFail();

        $historial = Matricula::where('estudiante_id', $estudiante->id)
            ->with(['periodo', 'detalles.seccion.asignatura'])
            ->orderBy('periodo_id', 'desc')
            ->get();

        $creditosAprobados = 0;
        foreach($historial as $mat) {
            foreach($mat->detalles as $det) {
                if($det->estado_curso === 'aprobado') {
                    $creditosAprobados += $det->seccion->asignatura->creditos;
                }
            }
        }

        return view('estudiante.historial', compact('estudiante', 'historial', 'creditosAprobados'));
    }

    public function matricula()
    {
        $user = Auth::user();
        $estudiante = Estudiante::with(['carrera.escuela.facultad', 'detalle'])
                        ->where('usuario_id', $user->id)
                        ->firstOrFail();

        $periodoActual = PeriodoAcademico::latest('id')->first();
        if (!$periodoActual) {
            return redirect()->back()->with('error', 'No hay periodo de matrícula activo.');
        }

        $ultimaMatricula = Matricula::where('estudiante_id', $estudiante->id)->latest('id')->first();
        $esRegular = true;
        
        if ($ultimaMatricula) {
            $cursosJalados = DetalleMatricula::where('matricula_id', $ultimaMatricula->id)
                                ->where('estado_curso', 'desaprobado')
                                ->exists();
            if ($cursosJalados) $esRegular = false;
        }

        $resultadoServicio = $this->matriculaService->obtenerCursosDisponibles($estudiante->id);
        $cursosDisponibles = $resultadoServicio['secciones_disponibles'];

        $cursosAgrupados = $cursosDisponibles->groupBy(function($seccion) use ($estudiante) {
            $malla = MallaCurricular::where('carrera_id', $estudiante->carrera_id)
                        ->where('asignatura_id', $seccion->asignatura_id)
                        ->first();
            return $malla ? $malla->semestre : 'Electivos';
        });

        return view('estudiante.matricula', compact('estudiante', 'periodoActual', 'esRegular', 'cursosAgrupados'));
    }

    public function procesarMatricula(Request $request)
    {
        $request->validate([
            'secciones' => 'required|array|min:1',
            'secciones.*' => 'exists:asignatura_seccions,id'
        ]);

        $user = Auth::user();
        $estudiante = Estudiante::where('usuario_id', $user->id)->firstOrFail();

        try {
            $resultado = $this->matriculaService->registrarMatricula($estudiante->id, $request->secciones);
            
            return redirect()->route('estudiante.historial')
                    ->with('success', '¡Matrícula procesada con éxito! Código: ' . $resultado['codigo']);
                    
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al matricular: ' . $e->getMessage())->withInput();
        }
    }
}