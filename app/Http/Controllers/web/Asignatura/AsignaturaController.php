<?php

namespace App\Http\Controllers\web\Asignatura;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Asignatura;

class AsignaturaController extends Controller
{
    public function index()
    {
        $asignaturas = Asignatura::orderBy('nombre', 'asc')->paginate(15);
        return view('admin.asignatura.index', compact('asignaturas'));
    }

    public function create()
    {
        return view('admin.asignatura.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'codigo_asignatura' => 'required|unique:asignaturas,codigo_asignatura',
            'nombre' => 'required|string|max:100',
            'creditos' => 'required|integer|min:1|max:10',
        ]);

        Asignatura::create($request->all());

        return redirect()->route('admin.asignaturas.index')
                ->with('success', 'Asignatura creada correctamente.');
    }
    
    // Opcional: Método para editar/eliminar si lo necesitas a futuro
}