<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MallaCurricular extends Model
{
    use HasFactory;
    
    protected $table = 'malla_curricular'; 

    protected $fillable = [
        'especialidad_id',
        'asignatura_id',
        'semestre',
        'asig_oblig',
        'estado'
    ];

    public function especialidad()
    {
        return $this->belongsTo(Especialidad::class,'especialidad_id');
    }

    public function asignatura()
    {
        return $this->belongsTo(Asignatura::class);
    }
}