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
         Schema::create('matriculas', function (Blueprint $table) {
            $table->id();
            // Relación con Estudiantes (Asegúrate de que la tabla 'estudiantes' exista)
            // Si aún no tienes tabla estudiantes, cambia la siguiente línea por: $table->unsignedBigInteger('estudiante_id');
            $table->foreignId('estudiante_id')->constrained('estudiantes')->onDelete('cascade');
            
            // Periodo (Ej: '2025-1')
            $table->string('periodo_id'); 
            
            $table->string('codigo_matricula')->unique();
            $table->string('id_tramite')->nullable(); // N° de Recibo o Trámite
            $table->date('fecha_matricula');
            $table->string('estado')->default('Pendiente'); // Matriculado, Pendiente, Observado
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matriculas');
    }
};
