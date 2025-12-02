<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Profesor extends Model
{
    use HasFactory;

    protected $table = 'profesores';

    protected $fillable = [
        'usuario_id',
        'nombres',
        'apellidos',
        'dni',
        'correo_personal',
        'correo_institucional',
        'telefono',
        'estado'
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function detalles()
    {
        return $this->hasOne(DetallesProfesor::class, 'profesor_id');
    }
}
