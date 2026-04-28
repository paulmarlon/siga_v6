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
        Schema::create('pensums', function (Blueprint $table) {
            $table->id();

            // Relación con Carrera (Especialidad o Tronco Común)
            $table->foreignId('carrera_id')
                ->constrained('carreras')
                ->cascadeOnDelete();

            // Relación con la Materia
            $table->foreignId('materia_id')
                ->constrained('materias')
                ->cascadeOnDelete();

            // Relación con el Grado (Semestre)
            $table->foreignId('grado_id')
                ->constrained('grados')
                ->cascadeOnDelete();

            // Configuración de la materia en este pensum
            $table->boolean('es_obligatoria')->default(true);

            // Horas académicas (Opcional, pero útil para mallas)
            $table->integer('horas_semanales')->default(0);

            // Control de Estado y Auditoría
            $table->foreignId('estado_id')->default(1)->constrained('estados');
            $table->timestamps();
            $table->softDeletes();

            /**
             * ÍNDICES DE INTEGRIDAD
             */
            // 1. Evita que una materia se repita en la misma CARRERA (independientemente del grado)
            // Esto es clave para que no aparezca "Matemáticas" en 1ro y luego en 3ro de la misma carrera.
            $table->unique(['carrera_id', 'materia_id'], 'unique_materia_carrera_pensum');

            // 2. Índice compuesto para acelerar búsquedas de la malla curricular completa
            $table->index(['carrera_id', 'grado_id'], 'idx_malla_curricular');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pensums');
    }
};
