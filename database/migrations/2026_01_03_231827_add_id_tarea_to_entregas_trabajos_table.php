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
        Schema::table('entregas_trabajos', function (Blueprint $table) {
            $table->unsignedBigInteger('id_tarea')->nullable()->after('id_unidad');
            $table->foreign('id_tarea')->references('id_tarea')->on('tareas')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('entregas_trabajos', function (Blueprint $table) {
            $table->dropForeign(['id_tarea']);
            $table->dropColumn('id_tarea');
        });
    }
};
