<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Escuela extends Model
{

    use HasFactory;
    protected $table = 'escuelas';
    public $timestamps = false;

    protected $fillable = ['nombre', 'codigo_interno', 'facultad_id'];


    public function facultad()
    {
        return $this->belongsTo(Facultad::class);
    }

    public function carreras()
    {
        return $this->hasMany(Carrera::class);
    }
}
