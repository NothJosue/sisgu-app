<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Horario extends Model
{
    use HasFactory;

    protected $table = 'horarios';

    protected $primaryKey = 'id_horario';

    protected $fillable = [
        'ID_Asignatura_Seccion',
        'ID_Dia',
        'ID_Bloque',
        'ID_Aula',
        'ID_Periodo',
        'Tipo_Sesion',
    ];

    public function seccion()
    {
        return $this->belongsTo(AsignaturaSeccion::class, 'ID_Asignatura_Seccion');
    }

    public function bloque()
    {
        return $this->belongsTo(BloqueHorario::class, 'ID_Bloque');
    }

    public function aula()
    {
        return $this->belongsTo(Aula::class, 'ID_Aula');
    }
}
