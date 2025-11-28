<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Profesor extends Model
{
    use HasFactory;

    protected $table = 'profesores';

    protected $fillable = [
        'id_usuario',
        'nombres',
        'apellidos',
        'DNI',
        'correo_personal',
        'correo_institucional',
        'telefono',
        'estado'
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    public function detalles()
    {
        return $this->hasOne(DetallesProfesor::class, 'id_profesor');
    }
}
