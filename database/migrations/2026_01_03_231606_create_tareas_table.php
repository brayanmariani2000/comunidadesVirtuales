<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tareas', function (Blueprint $table) {
            $table->id('id_tarea');
            $table->unsignedBigInteger('id_chat');
            $table->unsignedBigInteger('id_profesor');
            $table->string('titulo', 200);
            $table->text('descripcion')->nullable();
            $table->timestamp('fecha_limite')->nullable();
            $table->timestamps();

            $table->foreign('id_chat')->references('id_chat')->on('chats')->onDelete('cascade');
            $table->foreign('id_profesor')->references('id_usuario')->on('usuarios')->onDelete('cascade');
        });
    }

    /**
     * Revertir las migraciones.
     */
    public function down(): void
    {
        Schema::dropIfExists('tareas');
    }
};
