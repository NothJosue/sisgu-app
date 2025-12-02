<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MallaCurricular extends Model
{
    use HasFactory;

    protected $table = 'malla_curricular';
    public $timestamps = false;

    protected $fillable = [
        'asignatura_id',
        'carrera_id',
        'semestre',
        'tipo_curso',
        'activo',
    ];

    public function asignatura()
    {
        return $this->belongsTo(Asignatura::class);
    }

    public function carrera()
    {
        return $this->belongsTo(Carrera::class);
    }
}
