<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facultad extends Model
{
    use HasFactory;
    protected $table = 'facultades';
    public $timestamps = false;

    protected $fillable = ['nombre', 'codigo_interno'];

    public function escuelas()
    {
        return $this->hasMany(Escuela::class);
    }
}
