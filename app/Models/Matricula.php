<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Matricula extends Model
{
    use HasFactory;

    protected $table = 'matricula';

    protected $fillable = [
        'id_tramite',
        'id_estudiante',
        'id_Asignatura_seccion',
        'repeticiones',
        'turno',
        'fechaMatricula',
    ];

    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class, 'id_estudiante');
    }

    public function seccion()
    {
        return $this->belongsTo(AsignaturaSeccion::class, 'id_Asignatura_seccion');
    }
}
