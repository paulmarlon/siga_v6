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
        Schema::table('users', function (Blueprint $table) {
            // 1. Añadimos la columna persona_id después del ID
            // constrained('personas') busca la tabla 'personas' que ya debe existir
            $table->foreignId('persona_id')
                ->after('id')
                ->constrained('personas')
                ->onDelete('cascade');

            // 2. Añadimos la columna estado_id para el control de acceso
            $table->foreignId('estado_id')
                ->after('persona_id')
                ->constrained('estados');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Eliminamos las llaves foráneas primero
            $table->dropForeign(['persona_id']);
            $table->dropForeign(['estado_id']);

            // Luego eliminamos las columnas
            $table->dropColumn(['persona_id', 'estado_id']);
        });
    }
};
