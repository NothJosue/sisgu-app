<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('malla_curricular', function (Blueprint $table) {
            $table->id();
            $table->foreignId('especialidad_id')->constrained('especialidades');
            $table->foreignId('asignatura_id')->constrained('asignaturas');
            $table->integer('semestre');
            $table->string('asig_oblig', 20);
            $table->string('estado', 20);
            $table->timestamps();
            $table->unique(['especialidad_id', 'asignatura_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('malla_curricular');
    }
};