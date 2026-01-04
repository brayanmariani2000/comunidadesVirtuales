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
        Schema::create('participantes_chat', function (Blueprint $table) {
            $table->id('id_participante');
            $table->unsignedBigInteger('id_chat');
            $table->unsignedBigInteger('id_usuario');
            $table->enum('rol_participante', ['creador', 'administrador', 'miembro'])->default('miembro');
            $table->boolean('activo')->default(true);
            $table->timestamp('fecha_union')->useCurrent();
            $table->timestamp('fecha_salida')->nullable();
            
            $table->foreign('id_chat')->references('id_chat')->on('chats')->onDelete('cascade');
            $table->foreign('id_usuario')->references('id_usuario')->on('usuarios')->onDelete('cascade');
            
            // Un usuario no puede estar duplicado en el mismo chat
            $table->unique(['id_chat', 'id_usuario']);
        });
    }

    /**
     * Revertir las migraciones.
     */
    public function down(): void
    {
        Schema::dropIfExists('participantes_chat');
    }
};
