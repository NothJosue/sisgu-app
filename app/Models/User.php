<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // 1. Apuntamos a tu tabla personalizada
    protected $table = 'usuarios';

    // 2. Definimos tus campos
    protected $fillable = [
        'username',
        'password',
        'rol',
        'estado',
    ];

    // 3. Ocultamos el password
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // 4. Casteamos el password para que Laravel sepa que está encriptado
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }
}