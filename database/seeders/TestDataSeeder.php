<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Estudiante;
use App\Models\Carrera; // Asegúrate de tener carreras creadas o crea una aquí
use Illuminate\Support\Facades\Hash;

class TestDataSeeder extends Seeder
{
    /* public function run(): void
    {
        // 1. Buscamos el ID de la carrera automáticamente para no equivocarnos
        $carreraSistemas = Carrera::where('nombre', 'Ingeniería Informática')->first();

        // Si no existe (por si acaso no corriste los inserts SQL), evitamos error
        if (!$carreraSistemas) {
            $this->command->error('¡Cuidado! No encontré la carrera de Ingeniería Informática.');
            return;
        }

        // 2. Crear Usuario
        $user = User::create([
            'username' => '2022015428',
            'password' => Hash::make('2022015428'),
            'rol' => 'Estudiante',
            'estado' => 'Activo'
        ]);

        // 3. Crear Estudiante vinculado
        Estudiante::create([
            'usuario_id'        => $user->id,
            'carrera_id'        => $carreraSistemas->id,
            'codigo_estudiante' => '2022015428',
            'nombres'           => 'Josue',
            'apellidos'         => 'Albarracin Rivera',
            'dni'               => '72389816',
            'estado'            => 'Activo',
            'codigo_programa'   => '130200' 
        ]);
    } */
}