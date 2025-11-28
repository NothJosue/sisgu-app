<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prerequisitos', function (Blueprint $table) {
            $table->id();

            // Asignatura que tiene el requisito
            $table->foreignId('asignatura_id')
                  ->constrained('asignaturas')
                  ->cascadeOnDelete();

            // Asignatura que es el requisito
            $table->foreignId('requisito_id')
                  ->constrained('asignaturas')
                  ->cascadeOnDelete();

            $table->timestamps();

            $table->unique(['asignatura_id', 'requisito_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prerequisitos');
    }
};
