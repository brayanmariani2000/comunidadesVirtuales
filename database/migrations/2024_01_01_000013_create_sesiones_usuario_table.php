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
        Schema::create('sesiones_usuario', function (Blueprint $table) {
            $table->id('id_sesion');
            $table->unsignedBigInteger('id_usuario');
            $table->string('direccion_ip', 45);
            $table->text('user_agent')->nullable();
            $table->timestamp('fecha_inicio')->useCurrent();
            $table->timestamp('fecha_fin')->nullable();
            $table->boolean('activa')->default(true);
            
            $table->foreign('id_usuario')->references('id_usuario')->on('usuarios')->onDelete('cascade');
            
            $table->index(['id_usuario', 'fecha_inicio']);
        });
    }

    /**
     * Revertir las migraciones.
     */
    public function down(): void
    {
        Schema::dropIfExists('sesiones_usuario');
    }
};
