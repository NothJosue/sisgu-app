<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetalleMatricula extends Model
{
    use HasFactory;

    protected $table = 'detalle_matriculas';

    protected $fillable = [
        'matricula_id',
        'asignatura_seccion_id',
        'nota_final',
        'estado_curso',
        'vez_cursado'
    ];

    public function matricula()
    {
        return $this->belongsTo(Matricula::class, 'matricula_id');
    }

    public function seccion()
    {
        return $this->belongsTo(AsignaturaSeccion::class, 'asignatura_seccion_id');
    }
}