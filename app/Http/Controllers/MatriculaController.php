<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Matricula;
// use App\Models\Estudiante; // Descomentar cuando tengas el modelo Estudiante

class MatriculaController extends Controller
{
    /**
     * Muestra la página principal de matrículas.
     */
    public function index()
    {
        // Intentamos obtener datos reales. Usamos try/catch para que no te salga error
        // si aún no has migrado la base de datos o te faltan tablas.
        try {
            // Obtener matrículas con la información del estudiante (si existe la relación)
            // Asegúrate de que tu modelo Matricula tenga: public function estudiante() { ... }
            $matriculas = Matricula::with('estudiante')->orderBy('created_at', 'desc')->get();
            
            // Obtener estudiantes para el select del formulario
            // $estudiantes = Estudiante::all(); 
            
            // TEMPORAL: Array vacío hasta que tengas el modelo Estudiante listo
            $estudiantes = []; 
        } catch (\Exception $e) {
            // Si falla la BD, enviamos listas vacías para que la vista cargue igual
            $matriculas = [];
            $estudiantes = [];
        }

        // Retornamos la vista que te pasé antes (resources/views/matricula/index.blade.php)
        return view('matricula.index', compact('matriculas', 'estudiantes'));
    }

    /**
     * Guarda una nueva matrícula.
     */
    public function store(Request $request)
    {
        // 1. Aquí iría la validación
        // $request->validate([ ... ]);

        // 2. Aquí iría el guardado
        // Matricula::create($request->all());

        // 3. Redirección
        // IMPORTANTE: Usamos 'admin.matriculas.index' porque la ruta está dentro del prefijo 'admin'
        return redirect()->route('admin.matriculas.index');
    }
}