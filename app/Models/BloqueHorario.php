<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BloqueHorario extends Model
{
    use HasFactory;

    protected $table = 'bloques_horarios';

    protected $fillable = [
        'Hora_Inicio',
        'Hora_Fin',
    ];

    public function horarios()
    {
        return $this->hasMany(Horario::class, 'ID_Bloque');
    }
}
