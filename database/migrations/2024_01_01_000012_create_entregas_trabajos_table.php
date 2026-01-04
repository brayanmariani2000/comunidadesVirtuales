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
        Schema::create('entregas_trabajos', function (Blueprint $table) {
            $table->id('id_entrega');
            $table->unsignedBigInteger('id_estudiante');
            $table->unsignedBigInteger('id_unidad');
            $table->string('titulo', 200);
            $table->text('descripcion')->nullable();
            $table->string('nombre_archivo', 255);
            $table->string('ruta_archivo', 500);
            $table->enum('estado', ['pendiente', 'revisado', 'aprobado', 'rechazado'])->default('pendiente');
            $table->decimal('calificacion', 5, 2)->nullable();
            $table->text('comentarios_profesor')->nullable();
            $table->timestamp('fecha_entrega')->useCurrent();
            $table->timestamp('fecha_revision')->nullable();
            
            $table->foreign('id_estudiante')->references('id_estudiante')->on('estudiantes')->onDelete('cascade');
            $table->foreign('id_unidad')->references('id_unidad')->on('unidades_curriculares')->onDelete('cascade');
        });
    }

    /**
     * Revertir las migraciones.
     */
    public function down(): void
    {
        Schema::dropIfExists('entregas_trabajos');
    }
};
