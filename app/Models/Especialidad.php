<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Especialidad extends Model
{
    use HasFactory;
    protected $table = 'especialidades';
    public $timestamps = false;

    protected $fillable = ['nombre', 'carrera_id', 'codigo_especialidad'];

    public function carrera()
    {
        return $this->belongsTo(Carrera::class);
    }

    public function mallaCurricular()
    {
        return $this->hasMany(MallaCurricular::class);
    }
}
