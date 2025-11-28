<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Asignatura extends Model
{
    use HasFactory;

    protected $table = 'asignaturas';

    protected $fillable = [
        'codigo_asignatura', 
        'nombre', 
        'creditos', 
        'asig_prerequi'
    ];

    public function mallas()
    {
        return $this->hasMany(MallaCurricular::class, 'asignatura_id');
    }

    // Cursos que esta asignatura requiere
    public function prerequisitos(): BelongsToMany
    {
        return $this->belongsToMany(
            Asignatura::class,
            'prerequisitos',
            'asignatura_id',
            'requisito_id'
        );
    }

    // Cursos para los que esta asignatura es requisito
    public function esPrerequisitoDe(): BelongsToMany
    {
        return $this->belongsToMany(
            Asignatura::class,
            'prerequisitos',
            'requisito_id',
            'asignatura_id'
        );
    }
}
