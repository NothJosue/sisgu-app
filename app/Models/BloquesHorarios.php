<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BloquesHorarios extends Model
{
    use HasFactory;

    protected $table = 'bloques_horarios';

    protected $fillable = [
        'hora_inicio',
        'hora_fin',
    ];

    public function horarios()
    {
        return $this->hasMany(Horarios::class, 'ID_Bloque');
    }
}
