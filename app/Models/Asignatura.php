<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asignatura extends Model
{
    use HasFactory;

    protected $table = 'asignaturas';

    protected $fillable = [
        'codigo_asignatura', 
        'nombre', 
        'creditos', 
        'asig_prerequi'
    ];

    /**
     * Relación: Una asignatura aparece en muchas mallas
     * (Ej: "Matemática I" puede estar en la malla de Sistemas y en la de Industrial)
     */
    public function mallas()
    {
        return $this->hasMany(MallaCurricular::class);
    }
}