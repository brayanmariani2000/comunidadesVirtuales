<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Ejecutar las migraciones.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE mensajes MODIFY COLUMN tipo_mensaje ENUM('texto', 'documento', 'imagen', 'video', 'tarea') NOT NULL DEFAULT 'texto'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE mensajes MODIFY COLUMN tipo_mensaje ENUM('texto', 'documento', 'imagen', 'video') NOT NULL DEFAULT 'texto'");
    }
};
