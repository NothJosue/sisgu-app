<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estudiante extends Model
{
    use HasFactory;

    protected $table = 'estudiantes';

    // Usamos los nombres reales de la BD (snake_case)
    protected $fillable = [
        'usuario_id',
        'carrera_id',
        'codigo_universitario',
        'anio_ingreso',
        'correo_institucional',
        'nombres',
        'apellidos',
        'dni',
        'estado'
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function carrera()
    {
        return $this->belongsTo(Carrera::class, 'carrera_id');
    }

    public function detalle()
    {
        // CORRECCIÓN: La llave foránea en BD es 'estudiante_id'
        return $this->hasOne(DetallesEstudiante::class, 'estudiante_id');
    }
}