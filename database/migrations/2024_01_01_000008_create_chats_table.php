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
        Schema::create('chats', function (Blueprint $table) {
            $table->id('id_chat');
            $table->string('nombre_chat', 100);
            $table->text('descripcion')->nullable();
            $table->unsignedBigInteger('id_unidad')->nullable();
            $table->enum('tipo_chat', ['privado', 'grupal', 'unidad_curricular'])->default('grupal');
            $table->unsignedBigInteger('creado_por');
            $table->boolean('activo')->default(true);
            $table->timestamp('fecha_creacion')->useCurrent();
            $table->timestamp('fecha_actualizacion')->useCurrent()->useCurrentOnUpdate();
            
            $table->foreign('id_unidad')->references('id_unidad')->on('unidades_curriculares')->onDelete('set null');
            $table->foreign('creado_por')->references('id_usuario')->on('usuarios')->onDelete('restrict');
        });
    }

    /**
     * Revertir las migraciones.
     */
    public function down(): void
    {
        Schema::dropIfExists('chats');
    }
};
