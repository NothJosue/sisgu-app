<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Escuela extends Model
{
    use HasFactory;

    protected $table = 'escuelas';
    public $timestamps = false;

    protected $fillable = [
        'facultad_id',
        'nombre',
        'codigo_interno',
    ];

    public function facultad()
    {
        return $this->belongsTo(Facultad::class);
    }

    public function carreras()
    {
        return $this->hasMany(Carrera::class);
    }
}
