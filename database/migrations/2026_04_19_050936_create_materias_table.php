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
        Schema::create('materias', function (Blueprint $table) {
            $table->id();
            $table->string('sigla', 20)->unique();
            $table->string('nombre', 100);
            $table->integer('horas_academicas');

            // Usamos string para que coincida con tu nota: Teorica, Tecnica, Laboratorio
            $table->string('tipo_materia');

            // El flag clave para el Tronco Común
            $table->boolean('es_comun')->default(false);

            // Relación con el estado (Activo, Papelera, etc.)
            $table->foreignId('estado_id')->constrained('estados');

            $table->timestamps();
            $table->softDeletes(); // Manteniendo tu estrategia de integridad
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materias');
    }
};
