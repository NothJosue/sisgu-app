<?php

namespace App\Services;

use App\Models\Estudiante;
use App\Models\Matricula;
use App\Models\DetalleMatricula;
use App\Models\AsignaturaSeccion;
use App\Models\PeriodoAcademico;
use Illuminate\Support\Facades\DB;
use Exception;

class MatriculaService
{
    public function obtenerCursosDisponibles($estudianteId)
    {
        $periodoActual = PeriodoAcademico::latest('id')->first();
        if (!$periodoActual) throw new Exception("No hay periodo activo.");

        $secciones = AsignaturaSeccion::where('periodo_id', $periodoActual->id)
            ->with(['asignatura', 'profesor', 'horarios'])
            ->get();

        return [
            'periodo' => $periodoActual,
            'secciones_disponibles' => $secciones
        ];
    }

    public function registrarMatricula($estudianteId, array $seccionesIds)
    {
        return DB::transaction(function () use ($estudianteId, $seccionesIds) {
            
            $estudiante = Estudiante::findOrFail($estudianteId);
            $periodoActual = PeriodoAcademico::latest('id')->first(); 

            if (!$periodoActual) throw new Exception("No hay periodo activo.");

            // 1. Crear o Recuperar la CABECERA
            $matricula = Matricula::firstOrCreate(
                [
                    'estudiante_id' => $estudianteId,
                    'periodo_id' => $periodoActual->id
                ],
                [
                    'codigo_matricula' => $periodoActual->codigo . '-' . $estudiante->codigo_universitario,
                    'id_tramite' => rand(10000, 99999),
                    'fecha_matricula' => now(),
                    'estado' => 'matriculado'
                ]
            );

            $detallesCreados = [];

            foreach ($seccionesIds as $seccionId) {
                // 2. Validar si ya tiene este curso específico inscrito
                $existe = DetalleMatricula::where('matricula_id', $matricula->id)
                    ->where('asignatura_seccion_id', $seccionId)
                    ->exists();

                if ($existe) continue;

                // 3. Crear el DETALLE
                $detalle = DetalleMatricula::create([
                    'matricula_id' => $matricula->id,
                    'asignatura_seccion_id' => $seccionId,
                    'estado_curso' => 'en_curso',
                    'vez_cursado' => 1, 
                    'nota_final' => null
                ]);

                $detallesCreados[] = $detalle;
            }

            return [
                'matricula_id' => $matricula->id,
                'codigo' => $matricula->codigo_matricula,
                'cursos_inscritos' => $detallesCreados
            ];
        });
    }
}