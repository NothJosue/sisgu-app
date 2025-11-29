<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Soportes extends Model
{
    use HasFactory;

    protected $table = 'soportes';

    protected $fillable = [
        'nombre',
        'tipo',
    ];
}
