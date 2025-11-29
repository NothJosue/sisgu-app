<?php

namespace App\Services;

use App\Models\User;
use App\Models\Estudiante;
use App\Models\Profesor;
use App\Models\Carrera;
use App\Models\DetallesEstudiante;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserService
{
    public function crearEstudiante(array $data)
    {
        return DB::transaction(function () use ($data) {
            // 1. Crear Usuario
            $user = User::create([
                'username' => $data['codigo_universitario'], // El código es el usuario
                'password' => Hash::make($data['dni']), // DNI como password inicial
                'rol' => 'Estudiante',
                'estado' => 'Activo'
            ]);

            // 2. Crear Perfil
            $estudiante = Estudiante::create([
                'usuario_id' => $user->id,
                'carrera_id' => $data['carrera_id'],
                'codigo_universitario' => $data['codigo_universitario'],
                'anio_ingreso' => $data['anio_ingreso'],
                'correo_institucional' => $data['correo_institucional'],
                'nombres' => $data['nombres'],
                'apellidos' => $data['apellidos'],
                'dni' => $data['dni'],
                'estado' => 'Activo'
            ]);

            // 3. Crear Detalle Inicial
            DetallesEstudiante::create([
                'estudiante_id' => $estudiante->id,
                'estado_matricula' => 'Regular',
                'fecha_ingreso' => now(),
                'promedio_general' => 0.00
            ]);

            return $estudiante;
        });
    }

    public function crearProfesor(array $data)
    {
        return DB::transaction(function () use ($data) {
            $user = User::create([
                'username' => $data['correo_institucional'], // Correo como usuario
                'password' => Hash::make($data['dni']),
                'rol' => 'Profesor',
                'estado' => 'Activo'
            ]);

            $profesor = Profesor::create([
                'usuario_id' => $user->id,
                'nombres' => $data['nombres'],
                'apellidos' => $data['apellidos'],
                'dni' => $data['dni'],
                'correo_institucional' => $data['correo_institucional'],
                'telefono' => $data['telefono'],
                'estado' => 'Activo'
            ]);

            return $profesor;
        });
    }
}