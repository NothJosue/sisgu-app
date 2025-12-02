<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetallesEstudiante extends Model
{
    use HasFactory;

    protected $table = 'detalles_estudiantes';

    protected $fillable = [
        'estudiante_id',
        'estado_matricula',
        'fecha_ingreso',
        'promedio_general',
        'observaciones',
    ];

    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class);
    }
}
