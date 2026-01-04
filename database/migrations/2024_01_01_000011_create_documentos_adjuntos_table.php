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
        Schema::create('documentos_adjuntos', function (Blueprint $table) {
            $table->id('id_documento');
            $table->unsignedBigInteger('id_mensaje');
            $table->string('nombre_archivo', 255);
            $table->string('tipo_archivo', 100);
            $table->integer('tamano_archivo'); // en bytes
            $table->string('ruta_almacenamiento', 500);
            $table->timestamp('fecha_subida')->useCurrent();
            
            $table->foreign('id_mensaje')->references('id_mensaje')->on('mensajes')->onDelete('cascade');
        });
    }

    /**
     * Revertir las migraciones.
     */
    public function down(): void
    {
        Schema::dropIfExists('documentos_adjuntos');
    }
};
