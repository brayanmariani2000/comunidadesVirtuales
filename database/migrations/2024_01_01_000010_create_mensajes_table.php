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
        Schema::create('mensajes', function (Blueprint $table) {
            $table->id('id_mensaje');
            $table->unsignedBigInteger('id_chat');
            $table->unsignedBigInteger('id_usuario');
            $table->text('contenido_texto')->nullable();
            $table->enum('tipo_mensaje', ['texto', 'documento', 'imagen', 'video'])->default('texto');
            $table->boolean('eliminado')->default(false);
            $table->timestamp('fecha_envio')->useCurrent();
            $table->timestamp('fecha_edicion')->nullable();
            
            $table->foreign('id_chat')->references('id_chat')->on('chats')->onDelete('cascade');
            $table->foreign('id_usuario')->references('id_usuario')->on('usuarios')->onDelete('restrict');
            
            $table->index(['id_chat', 'fecha_envio']);
        });
    }

    /**
     * Revertir las migraciones.
     */
    public function down(): void
    {
        Schema::dropIfExists('mensajes');
    }
};
