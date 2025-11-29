<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Carrera extends Model
{
    use HasFactory;

    protected $table = 'carreras';

    protected $fillable = [
        'id_escuela',
        'nombre',
        'codigo_interno', 
    ];

    public function escuela()
    {
        return $this->belongsTo(Escuela::class, 'id_escuela');
    }

    public function especialidades()
    {
        return $this->hasMany(Especialidad::class, 'id_carrera');
    }

    public function estudiantes()
    {
        return $this->hasMany(Estudiante::class, 'id_carrera');
    }
}
