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
        Schema::create('profesores', function (Blueprint $table) {
            $table->unsignedBigInteger('id_profesor')->primary();
            $table->string('codigo_profesor', 20)->unique();
            $table->string('especialidad', 150)->nullable();
            $table->string('grado_academico', 100)->nullable();
            $table->date('fecha_contratacion')->nullable();
            $table->timestamp('fecha_actualizacion')->useCurrent()->useCurrentOnUpdate();
            
            $table->foreign('id_profesor')->references('id_usuario')->on('usuarios')->onDelete('cascade');
        });
    }

    /**
     * Revertir las migraciones.
     */
    public function down(): void
    {
        Schema::dropIfExists('profesores');
    }
};
