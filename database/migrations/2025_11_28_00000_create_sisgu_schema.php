<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ==========================================
        // 1. JERARQUÍA INSTITUCIONAL
        // ==========================================
        Schema::create('facultades', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 50)->unique();
            $table->string('codigo_interno', 2)->unique();
            $table->string('direccion', 100)->nullable();
            $table->timestamps();
        });

        Schema::create('escuelas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('facultad_id')->constrained('facultades');
            $table->string('nombre', 100);
            $table->string('codigo_interno', 2);
            $table->timestamps();
        });

        Schema::create('carreras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('escuela_id')->constrained('escuelas');
            $table->string('nombre', 100)->unique();
            $table->string('codigo_interno', 2);
            $table->timestamps();
            $table->unique(['escuela_id', 'codigo_interno']);
        });

        Schema::create('especialidades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('carrera_id')->constrained('carreras');
            $table->string('nombre', 100)->unique();
            $table->string('codigo_interno', 2);
            $table->string('estado', 20)->nullable();
            $table->timestamps();
        });

        // ==========================================
        // 2. INFRAESTRUCTURA
        // ==========================================
        Schema::create('edificios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('facultad_id')->nullable()->constrained('facultades');
            $table->string('nombre', 50);
            $table->string('direccion', 100)->nullable();
            $table->timestamps();
        });

        Schema::create('aulas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('edificio_id')->constrained('edificios');
            $table->string('nombre_aula', 50);
            $table->string('codigo_aula', 50)->unique()->nullable();
            $table->enum('tipo', ['Salon Regular', 'Laboratorio', 'Auditorio', 'Computo']);
            $table->integer('capacidad')->nullable();
            $table->integer('piso')->nullable();
            $table->string('estado', 20)->nullable();
            $table->timestamps();
        });

        // ==========================================
        // 3. ACTORES Y SEGURIDAD
        // ==========================================
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->string('username', 50)->unique();
            $table->string('password', 255);
            $table->enum('rol', ['Admin', 'Profesor', 'Estudiante', 'Soporte']);
            $table->string('estado', 20)->default('Activo');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('profesores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->unique()->constrained('usuarios');
            $table->string('nombres', 50);
            $table->string('apellidos', 100);
            $table->integer('dni')->unique();
            $table->string('correo_personal', 100)->unique()->nullable();
            $table->string('correo_institucional', 100)->unique()->nullable();
            $table->string('telefono', 14);
            $table->string('estado', 20)->nullable();
            $table->timestamps();
        });

        Schema::create('detalles_profesores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('profesor_id')->unique()->constrained('profesores')->cascadeOnDelete();
            $table->enum('estado_civil', ['Soltero', 'Casado', 'Divorciado', 'Viudo', 'Otro']);
            $table->boolean('fue_despedido')->default(false);
            $table->integer('anios_experiencia')->nullable();
            $table->date('fecha_contratacion')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });

        Schema::create('estudiantes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->unique()->constrained('usuarios');
            $table->foreignId('carrera_id')->constrained('carreras');
            $table->string('codigo_universitario', 15)->unique();
            $table->year('anio_ingreso');
            $table->string('correo_institucional', 40)->unique();
            $table->string('nombres', 50);
            $table->string('apellidos', 100);
            $table->integer('dni')->unique();
            $table->string('estado', 20);
            $table->timestamps();
        });

        Schema::create('detalles_estudiantes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('estudiante_id')->unique()->constrained('estudiantes')->cascadeOnDelete();
            $table->enum('estado_matricula', ['Regular', 'Irregular', 'Repetido', 'Suspendido', 'Expulsado']);
            $table->date('fecha_ingreso')->nullable();
            $table->decimal('promedio_general', 4, 2)->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });

        // ==========================================
        // 4. ACADÉMICO Y HORARIOS
        // ==========================================
        Schema::create('periodo_academicos', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 10)->nullable(); // Ej: 2025-1
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->enum('turno', ['M', 'T', 'N'])->nullable();
            $table->timestamps();
        });

        Schema::create('asignaturas', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_asignatura', 12)->unique()->nullable();
            $table->string('nombre', 30);
            $table->integer('creditos')->nullable();
            $table->string('asig_prerequi', 8)->nullable();
            $table->timestamps();
        });

        Schema::create('malla_curricular', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asignatura_id')->constrained('asignaturas');
            $table->foreignId('carrera_id')->constrained('carreras');
            $table->integer('semestre')->nullable();
            $table->string('asig_oblig', 20)->nullable();
            $table->string('estado', 20)->nullable();
            $table->timestamps();
            $table->unique(['carrera_id', 'asignatura_id']);
        });

        Schema::create('bloques_horarios', function (Blueprint $table) {
            $table->id();
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->timestamps();
            $table->unique(['hora_inicio', 'hora_fin']);
        });

        Schema::create('asignatura_seccions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asignatura_id')->constrained('asignaturas');
            $table->foreignId('profesor_id')->constrained('profesores');
            $table->foreignId('periodo_id')->constrained('periodo_academicos');
            $table->string('nombre_seccion', 5);
            $table->integer('cupos');
            $table->enum('modalidad', ['Presencial', 'Virtual'])->nullable();
            $table->timestamps();

            // Usamos un nombre corto personalizado para evitar el error 1059
            $table->unique(['asignatura_id', 'periodo_id', 'nombre_seccion'], 'uniq_asig_per_nom');
        });

        Schema::create('horarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asignatura_seccion_id')->constrained('asignatura_seccions')->cascadeOnDelete();
            $table->integer('dia_id'); // 1=Lunes...
            $table->foreignId('bloque_id')->constrained('bloques_horarios');
            $table->foreignId('aula_id')->constrained('aulas');
            $table->foreignId('periodo_id')->constrained('periodo_academicos');
            $table->enum('tipo_sesion', ['Teoría', 'Laboratorio', 'Practica'])->default('Teoría');
            $table->timestamps();

            $table->unique(['aula_id', 'dia_id', 'bloque_id', 'periodo_id'], 'aula_ocupada_uid');
            $table->unique(['asignatura_seccion_id', 'dia_id', 'bloque_id'], 'seccion_ocupada_uid');
        });

        // ==========================================
        // 5. TRANSACCIONAL - PAGOS
        // ==========================================
        Schema::create('soportes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100)->nullable();
            $table->string('tipo', 20)->nullable();
            $table->timestamps();
        });

        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('estudiante_id')->constrained('estudiantes')->cascadeOnDelete();
            $table->foreignId('soporte_id')->nullable()->constrained('soportes')->restrictOnDelete();
            $table->integer('codigo_pago')->nullable();
            $table->integer('codigo_banco')->nullable();
            $table->decimal('monto', 10, 2)->nullable();
            $table->date('fecha_pago')->nullable();
            $table->timestamps();
        });

        // ==========================================
        // 6. PRERREQUISITOS
        // ==========================================
        Schema::create('prerequisitos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asignatura_id')->constrained('asignaturas')->cascadeOnDelete();
            $table->foreignId('requisito_id')->constrained('asignaturas')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['asignatura_id', 'requisito_id']);
        });

        // ==========================================
        // 7. MATRICULA (CABECERA - ESTRUCTURA NUEVA)
        // ==========================================
        Schema::create('matriculas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('estudiante_id')->constrained('estudiantes')->cascadeOnDelete();
            $table->foreignId('periodo_id')->constrained('periodo_academicos')->restrictOnDelete();
            
            $table->string('codigo_matricula', 30)->unique()->nullable(); // Ej: 20251-20250050
            $table->integer('id_tramite')->nullable(); 
            $table->dateTime('fecha_matricula');
            $table->enum('estado', ['prematrícula', 'matriculado', 'observado', 'anulado'])->default('matriculado');
            
            $table->timestamps();

            // Regla: Un estudiante solo puede tener UNA ficha de matrícula por periodo
            $table->unique(['estudiante_id', 'periodo_id']);
        });

        // ==========================================
        // 8. DETALLE MATRICULA (LOS CURSOS INSCRITOS)
        // ==========================================
        Schema::create('detalle_matriculas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('matricula_id')->constrained('matriculas')->cascadeOnDelete();
            $table->foreignId('asignatura_seccion_id')->constrained('asignatura_seccions')->restrictOnDelete();
            
            $table->decimal('nota_final', 4, 2)->nullable();
            $table->enum('estado_curso', ['en_curso', 'aprobado', 'desaprobado', 'retirado', 'sin_nota'])->default('en_curso');
            $table->integer('vez_cursado')->default(1); // 1=Regular, 2=Segunda, 3=Tercera
            
            $table->timestamps();

            // Regla: No duplicar el curso en la misma ficha. Usamos nombre corto.
            $table->unique(['matricula_id', 'asignatura_seccion_id'], 'uniq_mat_det_sec');
        });

        // ==========================================
        // 9. TABLAS DE SISTEMA
        // ==========================================
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        // Borramos en orden inverso para respetar FKs
        Schema::dropIfExists('detalle_matriculas');
        Schema::dropIfExists('matriculas');
        Schema::dropIfExists('prerequisitos');
        Schema::dropIfExists('pagos');
        Schema::dropIfExists('soportes');
        Schema::dropIfExists('horarios');
        Schema::dropIfExists('asignatura_seccions');
        Schema::dropIfExists('bloques_horarios');
        Schema::dropIfExists('malla_curricular');
        Schema::dropIfExists('asignaturas');
        Schema::dropIfExists('periodo_academicos');
        Schema::dropIfExists('detalles_estudiantes');
        Schema::dropIfExists('estudiantes');
        Schema::dropIfExists('detalles_profesores');
        Schema::dropIfExists('profesores');
        Schema::dropIfExists('usuarios');
        Schema::dropIfExists('aulas');
        Schema::dropIfExists('edificios');
        Schema::dropIfExists('especialidades');
        Schema::dropIfExists('carreras');
        Schema::dropIfExists('escuelas');
        Schema::dropIfExists('facultades');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
    }
};