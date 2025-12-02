<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Asignatura extends Model
{
    use HasFactory;

    protected $table = 'asignaturas';

    public $timestamps = false;
    protected $fillable = [
        'codigo_asignatura', 
        'nombre', 
        'creditos', 
    ];

    public function mallas()
    {
        return $this->hasMany(MallaCurricular::class);
    }

    public function prerequisitos(): BelongsToMany
    {
        return $this->belongsToMany(
            Asignatura::class,
            'prerequisitos',
            'asignatura_id',
            'requisito_id'
        );
    }

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
