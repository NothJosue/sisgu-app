<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetallesEstudiante extends Model
{
    use HasFactory;

    protected $table = 'detalles_estudiante';
    public $timestamps = false;
    protected $fillable = [
        'estudiante_id',
        'estado_matricula',
        'fecha_ingreso',
        'promedio_general',
        'observaciones'
    ];

    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class);
    }
}