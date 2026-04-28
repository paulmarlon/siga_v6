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
        Schema::create('carreras', function (Blueprint $table) {
            $table->id();

            // Recursividad para Herencia (Clave para el Ciclo 1 / Tronco Común)
            $table->foreignId('carrera_base_id')->nullable()->constrained('carreras')->onDelete('restrict')->comment('Recursividad para permitir que una carrera dependa de un Tronco Común');
            $table->string('nombre');
            $table->string('sigla', 20)->unique();
            $table->string('resolucion')->nullable()->comment('Número de Resolución Ministerial');
            $table->integer('duracion')->comment('Total semestres (Suma de Base + Especialidad)');
            $table->string('titulo')->comment('Título académico que otorga la carrera');
            $table->foreignId('nivel_id')->constrained('nivels')->onDelete('restrict');
            $table->boolean('es_tronco_comun')->default(false)->comment('Define si esta carrera sirve de base para otras');
            $table->foreignId('estado_id')->default(1)->constrained('estados')->onDelete('restrict');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carreras');
    }
};
