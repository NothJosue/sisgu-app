<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pago extends Model
{
    use HasFactory;

    protected $table = 'pagos';

    protected $fillable = [
        'estudiante_id',
        'matricula_id',
        'codigo_operacion',
        'entidad_financiera',
        'monto',
        'fecha_pago',
        'ruta_imagen',
        'tipo_voucher',
        'estado'
    ];

    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class, 'estudiante_id');
    }

    public function matricula()
    {
        return $this->belongsTo(Matricula::class, 'matricula_id');
    }
}