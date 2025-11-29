<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Models\Estudiante;
use App\Models\Profesor;
use App\Models\Carrera;
use App\Models\Facultad;

class AdminController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    // LISTADOS
    public function listarEstudiantes()
    {
        $estudiantes = Estudiante::with('carrera')->paginate(20);
        return response()->json($estudiantes);
    }

    public function listarProfesores()
    {
        $profesores = Profesor::with('usuario')->paginate(20);
        return response()->json($profesores);
    }

    public function dashboardData()
    {
        return response()->json([
            'total_alumnos' => Estudiante::count(),
            'total_profesores' => Profesor::count(),
            'facultades' => Facultad::count(),
            'carreras' => Carrera::count()
        ]);
    }

    // CREACIÓN
    public function storeEstudiante(Request $request)
    {
        $data = $request->validate([
            'nombres' => 'required|string',
            'apellidos' => 'required|string',
            'dni' => 'required|numeric|unique:estudiantes,dni',
            'carrera_id' => 'required|exists:carreras,id',
            'anio_ingreso' => 'required|digits:4',
            // Generamos un código simple o lo pedimos
            'codigo_universitario' => 'required|unique:estudiantes,codigo_universitario',
            'correo_institucional' => 'required|email|unique:estudiantes,correo_institucional',
        ]);

        try {
            $estudiante = $this->userService->crearEstudiante($data);
            return response()->json(['message' => 'Estudiante creado', 'data' => $estudiante], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function storeProfesor(Request $request)
    {
        $data = $request->validate([
            'nombres' => 'required|string',
            'apellidos' => 'required|string',
            'dni' => 'required|numeric|unique:profesores,dni',
            'correo_institucional' => 'required|email|unique:profesores,correo_institucional',
            'telefono' => 'required|string'
        ]);

        try {
            $profesor = $this->userService->crearProfesor($data);
            return response()->json(['message' => 'Profesor creado', 'data' => $profesor], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}