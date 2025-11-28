<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Soporte extends Model
{
    use HasFactory;

    protected $table = 'soporte';

    protected $fillable = [
        'nombre',
        'tipo',
    ];
}
