<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carrera extends Model
{
    use HasFactory;
    protected $table = 'carreras';
    public $timestamps = false;
    protected $fillable = ['nombre', 'codigo_carrera', 'escuela_id'];

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
