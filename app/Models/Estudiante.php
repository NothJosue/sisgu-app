<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Estudiante extends Model
{
    use HasFactory;

    protected $table = 'estudiante';

    protected $fillable = [
        'id_usuario',
        'id_carrera',
        'codigo_universitario',
        'anio_ingreso',
        'correo_institucional',
        'nombres',
        'apellidos',
        'DNI',
        'estado'
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    public function carrera()
    {
        return $this->belongsTo(Carrera::class, 'id_carrera');
    }

    public function detalles()
    {
        return $this->hasOne(DetallesEstudiante::class, 'id_estudiante');
    }
}
