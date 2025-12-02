<?php

namespace App\Services;

use App\Models\Matricula;
use App\Models\Pago;
use App\Models\PeriodoAcademico;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Exception;

class MatriculaService
{
    public function registrarMatriculaRegular($estudiante, array $data)
    {
        $periodo = PeriodoAcademico::where('codigo', '2025-1')->first();
        
        if (!$periodo) {
            throw new Exception("No hay un periodo académico activo.");
        }

        // 2. Verificar si ya está matriculado
        $existe = Matricula::where('estudiante_id', $estudiante->id)
                           ->where('periodo_id', $periodo->id)
                           ->exists();

        if ($existe) {
            throw new Exception("Ya tienes una matrícula registrada para este periodo.");
        }

        // 3. Iniciar Transacción (Todo o Nada)
        return DB::transaction(function () use ($estudiante, $periodo, $data) {
            
            // A. Crear Cabecera de Matrícula
            $matricula = Matricula::create([
                'estudiante_id'    => $estudiante->id,
                'periodo_id'       => $periodo->id,
                'codigo_matricula' => $periodo->codigo . '-' . $estudiante->codigo_universitario,
                'fecha_matricula'  => now(),
                'estado'           => 'prematrícula', // Estado inicial antes de validación
            ]);

            // B. Procesar Boleta 1 (Obligatoria)
            $ruta1 = $data['boleta1_foto']->store('pagos/' . $periodo->codigo, 'public');

            Pago::create([
                'estudiante_id'      => $estudiante->id,
                'matricula_id'       => $matricula->id,
                'entidad_financiera' => $data['boleta1_banco'],
                'codigo_operacion'   => $data['boleta1_codigo'],
                'monto'              => $data['boleta1_monto'],
                'fecha_pago'         => $data['boleta1_fecha'],
                'ruta_imagen'        => $ruta1,
                'tipo_voucher'       => 'Boleta 1',
                'estado'             => 'Pendiente'
            ]);

            // C. Procesar Boleta 2 (Opcional)
            if (isset($data['boleta2_foto']) && $data['boleta2_foto']) {
                $ruta2 = $data['boleta2_foto']->store('pagos/' . $periodo->codigo, 'public');

                Pago::create([
                    'estudiante_id'      => $estudiante->id,
                    'matricula_id'       => $matricula->id,
                    'entidad_financiera' => $data['boleta2_banco'],
                    'codigo_operacion'   => $data['boleta2_codigo'],
                    'monto'              => $data['boleta2_monto'],
                    'fecha_pago'         => $data['boleta2_fecha'],
                    'ruta_imagen'        => $ruta2,
                    'tipo_voucher'       => 'Boleta 2',
                    'estado'             => 'Pendiente'
                ]);
            }

            return $matricula;
        });
    }
}