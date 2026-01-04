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
        Schema::create('unidades_curriculares', function (Blueprint $table) {
            $table->id('id_unidad');
            $table->string('codigo_unidad', 20)->unique();
            $table->string('nombre_unidad', 150);
            $table->text('descripcion')->nullable();
            $table->integer('creditos')->default(0);
            $table->integer('horas_semanales')->default(0);
            $table->string('periodo_academico', 50)->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamp('fecha_creacion')->useCurrent();
            $table->timestamp('fecha_actualizacion')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Revertir las migraciones.
     */
    public function down(): void
    {
        Schema::dropIfExists('unidades_curriculares');
    }
};
