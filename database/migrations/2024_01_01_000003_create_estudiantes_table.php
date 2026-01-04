<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecutar las migraciones.
     */
    public function up(): void
    {
        Schema::create('estudiantes', function (Blueprint $table) {
            $table->unsignedBigInteger('id_estudiante')->primary();
            $table->string('codigo_estudiante', 20)->unique();
            $table->date('fecha_ingreso')->nullable();
            $table->string('carrera', 100)->nullable();
            $table->integer('semestre_actual')->nullable();
            $table->timestamp('fecha_actualizacion')->useCurrent()->useCurrentOnUpdate();
            
            $table->foreign('id_estudiante')->references('id_usuario')->on('usuarios')->onDelete('cascade');
        });
    }

    /**
     * Revertir las migraciones.
     */
    public function down(): void
    {
        Schema::dropIfExists('estudiantes');
    }
};
