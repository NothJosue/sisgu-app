<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PeriodoAcademico extends Model
{
    use HasFactory;

    protected $table = 'periodo_academicos';

    protected $fillable = [
        'codigo',
        'fecha_inicio',
        'fecha_fin',
        'turno',
    ];
}
