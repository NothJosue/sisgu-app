<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AsignaturaSeccion extends Model
{
    use HasFactory;

    protected $table = 'asignatura_seccion';

    protected $primaryKey = 'ID_Asignatura_Seccion';

    protected $fillable = [
        'ID_Asignatura',
        'ID_Profesor',
        'ID_Periodo',
        'Nombre_Seccion',
        'Cupos',
        'Modalidad',
    ];

    public function asignatura()
    {
        return $this->belongsTo(Asignatura::class, 'ID_Asignatura');
    }

    public function profesor()
    {
        return $this->belongsTo(Profesor::class, 'ID_Profesor');
    }

    public function periodo()
    {
        return $this->belongsTo(PeriodoAcademico::class, 'ID_Periodo');
    }

    public function horarios()
    {
        return $this->hasMany(Horario::class, 'ID_Asignatura_Seccion');
    }

    public function matriculas()
    {
        return $this->hasMany(Matricula::class, 'id_Asignatura_seccion');
    }
}
