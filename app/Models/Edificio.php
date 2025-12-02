<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Edificio extends Model
{
    use HasFactory;

    protected $table = 'edificios';
    public $timestamps = false;

    protected $fillable = [
        'facultad_id',
        'nombre',
        'direccion',
    ];

    public function facultad()
    {
        return $this->belongsTo(Facultad::class, 'facultad_id');
    }

    public function aulas()
    {
        return $this->hasMany(Aula::class, 'edificio_id');
    }
}
