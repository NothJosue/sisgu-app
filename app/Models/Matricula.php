<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Matricula extends Model
{
    use HasFactory;

    protected $table = 'matriculas';

    protected $fillable = [
        'estudiante_id',
        'periodo_id',
        'codigo_matricula',
        'id_tramite',
        'fecha_matricula',
        'estado'
    ];

    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class, 'estudiante_id');
    }

    public function periodo()
    {
        return $this->belongsTo(PeriodoAcademico::class, 'periodo_id');
    }

    public function detalles()
    {
        return $this->hasMany(DetalleMatricula::class, 'matricula_id');
    }
}