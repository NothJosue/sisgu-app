<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('estudiantes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('carrera_id')->constrained('carreras');
            $table->string('codigo_estudiante',8)->unique();
            $table->string('nombres',50);
            $table->string('apellidos',100);
            $table->string('dni',10)->unique();
            $table->enum('estado',['activo','inactivo']);
            $table->string('codigo_programa',6);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estudiantes');
    }
};
