<?php

namespace App\Services;

use App\Models\Matricula;
use App\Models\Pago;
use App\Models\PeriodoAcademico;
use App\Models\DetalleMatricula;
use App\Models\AsignaturaSeccion;
use Illuminate\Support\Facades\DB;
use Exception;

class MatriculaService
{
    /**
     * Procesa la matrícula regular completa (Anual)
     */
    public function registrarMatriculaRegular($estudiante, array $data)
    {
        // 1. Obtener el Periodo Activo (Hardcodeado a 2025-1 según tu requerimiento)
        $periodo = PeriodoAcademico::where('codigo', '2025-1')->first();
        
        if (!$periodo) {
            throw new Exception("Error del sistema: No se encontró el periodo académico activo (2025-1).");
        }

        // 2. Validar Duplicidad: Un alumno no puede tener 2 matrículas en el mismo periodo
        $yaMatriculado = Matricula::where('estudiante_id', $estudiante->id)
                                  ->where('periodo_id', $periodo->id)
                                  ->exists();

        if ($yaMatriculado) {
            throw new Exception("Ya tienes una matrícula registrada o en proceso de validación para este periodo.");
        }

        // 3. VALIDACIÓN DE CRÉDITOS (Backend - Seguridad Crítica)
        // No confiamos solo en el JS del navegador. Calculamos los créditos reales desde la BD.
        $totalCreditos = 0;
        
        // Obtenemos las secciones seleccionadas (data['cursos'] es un array de IDs de secciones)
        $seccionesSeleccionadas = AsignaturaSeccion::with('asignatura')
            ->whereIn('id', $data['cursos'])
            ->get();

        foreach ($seccionesSeleccionadas as $seccion) {
            $totalCreditos += $seccion->asignatura->creditos;
        }

        // Reglas de Negocio (Régimen Anual)
        if ($totalCreditos < 12) {
            throw new Exception("No cumples con el mínimo de créditos requeridos (12). Seleccionaste: $totalCreditos créditos.");
        }
        if ($totalCreditos > 50) {
            throw new Exception("Excedes el límite máximo de créditos permitidos (50). Seleccionaste: $totalCreditos créditos.");
        }

        // 4. TRANSACCIÓN DE BASE DE DATOS (Todo o Nada)
        return DB::transaction(function () use ($estudiante, $periodo, $data, $seccionesSeleccionadas) {
            
            // A. Crear la Cabecera de la Matrícula
            $matricula = Matricula::create([
                'estudiante_id'    => $estudiante->id,
                'periodo_id'       => $periodo->id,
                'codigo_matricula' => $periodo->codigo . '-' . $estudiante->codigo_universitario, // Ej: 2025-1-20200055
                'fecha_matricula'  => now(),
                'estado'           => 'prematrícula', // Estado inicial para validación
            ]);

            // B. Guardar Boleta 1 (Obligatoria)
            $this->guardarPago($estudiante, $matricula, $data, 'boleta1', $periodo->codigo);

            // C. Guardar Boleta 2 (Opcional)
            // Verificamos si subió archivo en boleta 2
            if (isset($data['boleta2_foto']) && $data['boleta2_foto'] != null) {
                $this->guardarPago($estudiante, $matricula, $data, 'boleta2', $periodo->codigo);
            }

            // D. Guardar el Detalle de Cursos (Inscripción)
            foreach ($seccionesSeleccionadas as $seccion) {
                DetalleMatricula::create([
                    'matricula_id'          => $matricula->id,
                    'asignatura_seccion_id' => $seccion->id,
                    'nota_final'            => null,
                    'estado_curso'          => 'en_curso',
                    'vez_cursado'           => 1 // Por defecto 1 (Regular). Si manejas historial de bicas, aquí iría esa lógica.
                ]);
            }

            return $matricula;
        });
    }

    /**
     * Función privada auxiliar para evitar repetir código al guardar Boleta 1 y Boleta 2
     */
    private function guardarPago($estudiante, $matricula, $data, $prefix, $periodoFolder) {
        // 1. Guardar la imagen en el disco 'public'
        // Ruta final: storage/app/public/pagos/2025-1/archivo.jpg
        $rutaImagen = $data[$prefix.'_foto']->store('pagos/' . $periodoFolder, 'public');
        
        // 2. Crear registro en BD
        Pago::create([
            'estudiante_id'      => $estudiante->id,
            'matricula_id'       => $matricula->id,
            'entidad_financiera' => $data[$prefix.'_banco'],
            'codigo_operacion'   => $data[$prefix.'_codigo'],
            'monto'              => $data[$prefix.'_monto'],
            'fecha_pago'         => $data[$prefix.'_fecha'],
            'ruta_imagen'        => $rutaImagen,
            'tipo_voucher'       => ($prefix == 'boleta1') ? 'Boleta 1' : 'Boleta 2',
            'estado'             => 'Pendiente' // Se crea pendiente de validación por Soporte/Tesorería
        ]);
    }
}