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
        Schema::create('inscripciones_estudiantes', function (Blueprint $table) {
            $table->id('id_inscripcion');
            $table->unsignedBigInteger('id_estudiante');
            $table->unsignedBigInteger('id_unidad');
            $table->enum('estado', ['activo', 'retirado', 'culminado', 'reprobado'])->default('activo');
            $table->decimal('calificacion_final', 5, 2)->nullable();
            $table->timestamp('fecha_inscripcion')->useCurrent();
            $table->timestamp('fecha_actualizacion')->useCurrent()->useCurrentOnUpdate();
            
            $table->foreign('id_estudiante')->references('id_estudiante')->on('estudiantes')->onDelete('cascade');
            $table->foreign('id_unidad')->references('id_unidad')->on('unidades_curriculares')->onDelete('cascade');
            
            // Un estudiante no puede inscribirse dos veces en la misma unidad al mismo tiempo
            $table->unique(['id_estudiante', 'id_unidad']);
        });
    }

    /**
     * Revertir las migraciones.
     */
    public function down(): void
    {
        Schema::dropIfExists('inscripciones_estudiantes');
    }
};
