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
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id('id_usuario');
            $table->unsignedBigInteger('id_rol');
            $table->string('nombre', 100);
            $table->string('apellido', 100);
            $table->string('cedula', 20)->unique();
            $table->string('telefono', 20)->nullable();
            $table->string('correo', 150)->unique();
            $table->string('contrasena_hash', 255);
            $table->boolean('activo')->default(true);
            $table->timestamp('fecha_registro')->useCurrent();
            $table->timestamp('ultimo_acceso')->nullable();
            $table->rememberToken();
            
            $table->foreign('id_rol')->references('id_rol')->on('roles')->onDelete('restrict');
        });
    }

    /**
     * Revertir las migraciones.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
