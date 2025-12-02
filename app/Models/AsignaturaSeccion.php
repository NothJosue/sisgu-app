<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AsignaturaSeccion extends Model
{
    use HasFactory;

    protected $table = 'asignatura_seccions';

    protected $fillable = [
        'asignatura_id',  
        'profesor_id',    
        'periodo_id',     
        'nombre_seccion', // Ej: "A", "B"
        'cupos',          
        'modalidad',      // "Presencial", "Virtual"
    ];

    public function asignatura()
    {
        return $this->belongsTo(Asignatura::class, 'asignatura_id');
    }

    public function profesor()
    {
        return $this->belongsTo(Profesor::class, 'profesor_id');
    }

    public function periodo()
    {
        return $this->belongsTo(PeriodoAcademico::class, 'periodo_id');
    }

    public function horarios()
    {
        return $this->hasMany(Horarios::class, 'asignatura_seccion_id');
    }

    public function detallesMatricula()
    {
        return $this->hasMany(DetalleMatricula::class, 'asignatura_seccion_id');
    }
}