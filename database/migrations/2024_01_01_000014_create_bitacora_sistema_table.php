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
        Schema::create('bitacora_sistema', function (Blueprint $table) {
            $table->id('id_bitacora');
            $table->unsignedBigInteger('id_usuario')->nullable();
            $table->string('accion', 100);
            $table->string('modulo', 50);
            $table->text('descripcion')->nullable();
            $table->string('direccion_ip', 45)->nullable();
            $table->enum('nivel', ['info', 'warning', 'error', 'critical'])->default('info');
            $table->timestamp('fecha_hora')->useCurrent();
            
            $table->foreign('id_usuario')->references('id_usuario')->on('usuarios')->onDelete('set null');
            
            $table->index(['modulo', 'fecha_hora']);
            $table->index(['id_usuario', 'fecha_hora']);
        });
    }

    /**
     * Revertir las migraciones.
     */
    public function down(): void
    {
        Schema::dropIfExists('bitacora_sistema');
    }
};
