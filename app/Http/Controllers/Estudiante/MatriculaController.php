<?php

namespace App\Http\Controllers\Estudiante;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\MatriculaService;
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
        return view('estudiante.matricula.regular');
    }

    public function store(Request $request)
    {
        // 1. Validaciones
        $request->validate([
            // Boleta 1 (Obligatoria)
            'boleta1_banco'  => 'required|string',
            'boleta1_codigo' => 'required|string|unique:pagos,codigo_operacion',
            'boleta1_monto'  => 'required|numeric|min:1',
            'boleta1_fecha'  => 'required|date',
            'boleta1_foto'   => 'required|image|mimes:jpeg,png,jpg|max:2048',

            // Boleta 2 (Opcional, pero si manda foto, validamos todo)
            'boleta2_foto'   => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'boleta2_banco'  => 'required_with:boleta2_foto|string|nullable',
            'boleta2_codigo' => 'required_with:boleta2_foto|string|unique:pagos,codigo_operacion|nullable',
            'boleta2_monto'  => 'required_with:boleta2_foto|numeric|min:1|nullable',
            'boleta2_fecha'  => 'required_with:boleta2_foto|date|nullable',
        ], [
            'boleta1_codigo.unique' => 'El código de la Boleta 1 ya ha sido registrado anteriormente.',
            'boleta2_codigo.unique' => 'El código de la Boleta 2 ya ha sido registrado anteriormente.',
            'required_with' => 'Si subes una segunda boleta, debes completar todos sus campos.',
        ]);

        try {
            // Obtenemos el estudiante del usuario logueado
            // Asegúrate que tu User.php tenga la relación public function estudiante()
            $estudiante = Auth::user()->estudiante; 

            if (!$estudiante) {
                return back()->withErrors(['msg' => 'El usuario actual no tiene un perfil de estudiante asociado.']);
            }

            // Llamamos al servicio
            $this->matriculaService->registrarMatriculaRegular($estudiante, $request->all());

            return redirect()->route('estudiante.dashboard')->with('success', '¡Matrícula registrada exitosamente! Tus pagos están en validación.');

        } catch (\Exception $e) {
            return back()->withErrors(['msg' => 'Error al procesar la matrícula: ' . $e->getMessage()])->withInput();
        }
    }
}