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
        Schema::create('detalles_estudiantes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('estudiante_id')
                  ->unique()
                  ->constrained('estudiantes')
                  ->onDelete('cascade');
            $table->enum('estado_matricula', [
                'Regular', 
                'Irregular', 
                'Repetido', 
                'Suspendido', 
                'Expulsado'
            ])->default('Regular');
            $table->date('fecha_ingreso')->nullable();
            $table->decimal('promedio_general', 4, 2)->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalles_estudiantes');
    }
};
