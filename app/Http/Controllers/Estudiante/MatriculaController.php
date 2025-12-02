<?php

namespace App\Http\Controllers\Estudiante;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\MatriculaService;
use App\Models\MallaCurricular;
use Illuminate\Support\Facades\Auth;

class MatriculaController extends Controller
{
    protected $matriculaService;

    public function __construct(MatriculaService $matriculaService)
    {
        $this->matriculaService = $matriculaService;
    }

    public function create()
    {
        // 1. Validar que el usuario tenga perfil de estudiante
        // Asegúrate de que en User.php tengas la relación: public function estudiante() { return $this->hasOne(...); }
        $estudiante = Auth::user()->estudiante;

        if (!$estudiante) {
            return redirect()->back()->with('error', 'Error crítico: No se encontró un perfil de estudiante asociado a este usuario.');
        }

        // 2. Obtener Cursos Disponibles según la carrera del estudiante
        // Traemos la malla con la asignatura y sus secciones disponibles para el periodo actual
        $cursosDisponibles = MallaCurricular::with(['asignatura', 'asignatura.secciones' => function($query) {
                // Opcional: Filtrar secciones solo del periodo activo si tienes esa lógica
                // $query->where('periodo_id', $idPeriodoActual);
            }])
            ->where('carrera_id', $estudiante->carrera_id)
            ->where('activo', true)
            ->orderBy('semestre', 'asc')
            ->get();

        // 3. Definir reglas de negocio (Régimen Anual)
        $limites = [
            'min' => 12,
            'max' => 50 
        ];

        return view('estudiante.matricula.regular', compact('cursosDisponibles', 'limites', 'estudiante'));
    }

    public function store(Request $request)
    {
        // 1. Validaciones del Formulario
        $request->validate([
            // --- Validaciones Boleta 1 (Obligatoria) ---
            'boleta1_banco'  => 'required|string',
            'boleta1_codigo' => 'required|string', // unique:pagos,codigo_operacion se puede agregar si quieres ser estricto
            'boleta1_monto'  => 'required|numeric|min:1',
            'boleta1_fecha'  => 'required|date',
            'boleta1_foto'   => 'required|image|mimes:jpeg,png,jpg|max:2048',

            // --- Validaciones Boleta 2 (Opcional) ---
            'boleta2_foto'   => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'boleta2_banco'  => 'required_with:boleta2_foto|string|nullable',
            'boleta2_codigo' => 'required_with:boleta2_foto|string|nullable',
            'boleta2_monto'  => 'required_with:boleta2_foto|numeric|min:1|nullable',
            'boleta2_fecha'  => 'required_with:boleta2_foto|date|nullable',

            // --- Validaciones de Cursos ---
            'cursos'   => 'required|array|min:1', 
            'cursos.*' => 'exists:asignatura_seccions,id', // Verificamos que el ID enviado sea una sección válida
        ], [
            'cursos.required' => 'Debes seleccionar al menos una asignatura para matricularte.',
            'boleta1_foto.required' => 'Es obligatorio subir la foto del primer voucher.',
            'required_with' => 'Si subes una segunda boleta, debes completar todos sus datos.'
        ]);

        try {
            $estudiante = Auth::user()->estudiante;

            if (!$estudiante) {
                return back()->withErrors(['msg' => 'No se encontró el perfil del estudiante.']);
            }
            
            // 2. Llamamos al servicio para procesar la transacción
            $this->matriculaService->registrarMatriculaRegular($estudiante, $request->all());

            return redirect()->route('estudiante.dashboard')
                ->with('success', '¡Matrícula Anual registrada con éxito! Tu expediente está en proceso de validación.');

        } catch (\Exception $e) {
            // Si algo falla en el servicio (ej: validación de créditos), volvemos con el error
            return back()->withErrors(['msg' => 'Error al procesar la matrícula: ' . $e->getMessage()])->withInput();
        }
    }
}