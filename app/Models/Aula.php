<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Aula extends Model
{
    use HasFactory;

    protected $table = 'aulas';

    protected $fillable = [
        'edificio_id',
        'nombre_aula',
        'codigo_aula',
        'tipo',
        'capacidad',
        'piso',
        'estado',
    ];

    public function edificio()
    {
        return $this->belongsTo(Edificio::class, 'edificio_id');
    }

    public function horarios()
    {
        return $this->hasMany(Horario::class, 'aula_id');
    }
}
