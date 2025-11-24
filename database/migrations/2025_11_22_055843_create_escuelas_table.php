<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('escuelas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('facultad_id')->constrained('facultades');
            $table->string('nombre', 60);
            $table->string('codigo_interno', 3)->unique();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('escuelas');
    }
};
