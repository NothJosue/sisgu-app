<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pago extends Model
{
    use HasFactory;

    protected $table = 'pagos';

    protected $primaryKey = 'id_pago';

    protected $fillable = [
        'id_estudiante',
        'id_soporte',
        'codigo_pago',
        'codigo_banco',
        'monto',
        'fecha_pago',
    ];

    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class, 'id_estudiante');
    }

    public function soporte()
    {
        return $this->belongsTo(Soportes::class, 'id_soporte');
    }
}
