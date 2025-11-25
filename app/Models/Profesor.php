<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profesor extends Model
{
    use HasFactory;

    protected $table = 'profesores';
    public $timestamps = false;

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

    // Relación: El profesor "es" un Usuario del sistema
    public function usuario()
    {
        return $this->belongsTo(User::class);
    }
}