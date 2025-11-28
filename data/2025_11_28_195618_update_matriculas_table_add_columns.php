<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('matriculas', function (Blueprint $table) {
            // estudiante que se matricula
            $table->foreignId('estudiante_id')
                  ->after('id')
                  ->constrained('estudiantes')
                  ->cascadeOnDelete();

            // asignatura (versión simple sin secciones por ahora)
            $table->foreignId('asignatura_id')
                  ->after('estudiante_id')
                  ->constrained('asignaturas');

            $table->integer('repeticiones')->nullable()->after('asignatura_id');
            $table->string('turno', 50)->nullable()->after('repeticiones');
            $table->date('fecha_matricula')->nullable()->after('turno');

            // notas
            $table->decimal('nota_final', 4, 2)->nullable()->after('fecha_matricula');
            $table->enum('estado_curso', ['en_curso', 'aprobado', 'desaprobado', 'retirado'])
                  ->default('en_curso')
                  ->after('nota_final');

            // Un mismo estudiante no puede matricularse dos veces al mismo curso
            $table->unique(['estudiante_id', 'asignatura_id']);
        });
    }

    public function down(): void
    {
        Schema::table('matriculas', function (Blueprint $table) {
            $table->dropUnique(['estudiante_id', 'asignatura_id']);
            $table->dropForeign(['estudiante_id']);
            $table->dropForeign(['asignatura_id']);

            $table->dropColumn([
                'estudiante_id',
                'asignatura_id',
                'repeticiones',
                'turno',
                'fecha_matricula',
                'nota_final',
                'estado_curso',
            ]);
        });
    }
};
