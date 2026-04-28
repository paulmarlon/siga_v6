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
        Schema::create('periodos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100); // Ej: "Primer Semestre", "Invierno"
            // Relación con Gestión (Fase 2)
            $table->foreignId('gestion_id')->constrained('gestions')->onDelete('cascade');
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->foreignId('estado_id')->default(1)->constrained('estados');
            $table->timestamps();
            $table->softDeletes(); // Para mantener integridad histórica
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('periodos');
    }
};
