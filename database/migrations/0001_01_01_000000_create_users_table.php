<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // CAMBIO IMPORTANTE: La tabla se llamará 'usuarios', no 'users'
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->string('username', 50)->unique(); // Usamos username, no email
            $table->string('password');
            $table->enum('rol', ['Admin', 'Profesor', 'Estudiante', 'Soporte']);
            $table->string('estado', 20)->default('Activo');
            $table->rememberToken();
            $table->timestamps();
        });

        // Tablas auxiliares de Laravel (opcionales pero recomendadas)
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usuarios');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};