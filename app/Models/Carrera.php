<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Carrera extends Model
{
    use HasFactory;

    protected $table = 'carreras';
    public $timestamps = false;
    protected $fillable = [
        'escuela_id',
        'nombre',
        'codigo_interno', 
    ];

    public function escuela()
    {
        return $this->belongsTo(Escuela::class);
    }

    public function especialidades()
    {
        return $this->hasMany(Especialidad::class);
    }

    public function estudiantes()
    {
        return $this->hasMany(Estudiante::class);
    }
}
