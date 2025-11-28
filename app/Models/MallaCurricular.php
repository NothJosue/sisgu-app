<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MallaCurricular extends Model
{
    use HasFactory;

    protected $table = 'malla_curricular';

    protected $fillable = [
        'id_asignatura',
        'id_carrera',
        'semestre',
        'asig_oblig',
        'estado',
    ];

    public function asignatura()
    {
        return $this->belongsTo(Asignatura::class, 'id_asignatura');
    }

    public function carrera()
    {
        return $this->belongsTo(Carrera::class, 'id_carrera');
    }
}
