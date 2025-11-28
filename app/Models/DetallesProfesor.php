<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetallesProfesor extends Model
{
    use HasFactory;

    protected $table = 'detalles_profesor';

    protected $fillable = [
        'id_profesor',
        'estado_civil',
        'fue_despedido',
        'anios_experiencia',
        'fecha_contratacion',
        'observaciones'
    ];

    public function profesor()
    {
        return $this->belongsTo(Profesor::class, 'id_profesor');
    }
}
