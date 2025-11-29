<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prerequisito extends Model
{
    use HasFactory;

    protected $table = 'prerequisitos';

    protected $fillable = [
        'asignatura_id', // El curso que se quiere llevar (Ej: Mate 2)
        'requisito_id',  // El curso que se debe haber aprobado antes (Ej: Mate 1)
    ];

    /**
     * El curso PRINCIPAL (el que tiene el requisito).
     * Ej: Si esto es la fila de "Mate 2", esta función devuelve "Mate 2".
     */
    public function asignatura()
    {
        return $this->belongsTo(Asignatura::class, 'asignatura_id');
    }

    /**
     * El curso REQUISITO (el necesario para llevar el principal).
     * Ej: Esta función devuelve "Mate 1".
     */
    public function requisito()
    {
        return $this->belongsTo(Asignatura::class, 'requisito_id');
    }
}