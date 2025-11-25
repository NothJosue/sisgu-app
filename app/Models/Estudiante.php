<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estudiante extends Model
{
    use HasFactory;

    protected $table = 'estudiantes';
    
    protected $fillable = [
        'usuario_id',
        'carrera_id',
        'codigo_estudiante', 
        'nombres', 
        'apellidos', 
        'dni', 
        'estado', 
        'codigo_programa'
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class);
    }

    public function carrera()
    {
        return $this->belongsTo(Carrera::class);
    }

    public function detalle()
    {
        return $this->hasOne(DetallesEstudiante::class);
    }
}
