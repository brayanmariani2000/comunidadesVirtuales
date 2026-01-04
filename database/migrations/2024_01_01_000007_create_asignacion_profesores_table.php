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
        Schema::create('asignacion_profesores', function (Blueprint $table) {
            $table->id('id_asignacion');
            $table->unsignedBigInteger('id_profesor');
            $table->unsignedBigInteger('id_unidad');
            $table->enum('rol_profesor', ['titular', 'asistente', 'auxiliar'])->default('titular');
            $table->timestamp('fecha_asignacion')->useCurrent();
            $table->timestamp('fecha_fin')->nullable();
            $table->boolean('activo')->default(true);
            
            $table->foreign('id_profesor')->references('id_profesor')->on('profesores')->onDelete('cascade');
            $table->foreign('id_unidad')->references('id_unidad')->on('unidades_curriculares')->onDelete('cascade');
        });
    }

    /**
     * Revertir las migraciones.
     */
    public function down(): void
    {
        Schema::dropIfExists('asignacion_profesores');
    }
};
