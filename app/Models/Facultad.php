<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Facultad extends Model
{
    use HasFactory;

    protected $table = 'facultades';
    public $timestamps = false;
    protected $fillable = [
        'nombre',
        'codigo_interno',
        'direccion',
    ];

    public function escuelas()
    {
        return $this->hasMany(Escuela::class);
    }
}
