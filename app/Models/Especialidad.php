<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Especialidad extends Model
{
    use HasFactory;

    protected $table = 'especialidades';

    public $timestamps = false;
    protected $fillable = [
        'carrera_id',
        'nombre',
        'codigo_interno',
        'estado',
    ];

    public function carrera()
    {
        return $this->belongsTo(Carrera::class);
    }
}
