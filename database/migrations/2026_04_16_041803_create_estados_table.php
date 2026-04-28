<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('estados', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 50)->unique();
            $table->string('slug', 50)->unique();
            $table->string('tipo', 30); // GLOBAL, ACADEMICO, ESTUDIANTE, etc.
            $table->boolean('permite_login')->default(true);
            $table->boolean('permite_procesos_academicos')->default(true);
            $table->string('color_hex', 10)->default('#6c757d');
            $table->timestamps();
        });

        // Insertar datos iniciales clasificados
        DB::table('estados')->insert([
            [
                'nombre' => 'ACTIVO',
                'slug' => 'activo',
                'tipo' => 'GLOBAL', // Disponible para Gestiones, Turnos, etc.
                'permite_login' => true,
                'permite_procesos_academicos' => true,
                'color_hex' => '#28a745',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'INACTIVO',
                'slug' => 'inactivo',
                'tipo' => 'GLOBAL', // Para gestiones pasadas o turnos cerrados
                'permite_login' => false,
                'permite_procesos_academicos' => false,
                'color_hex' => '#6c757d',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'SUSPENDIDO',
                'slug' => 'suspendido',
                'tipo' => 'ESTUDIANTE', // Específico para personas
                'permite_login' => false,
                'permite_procesos_academicos' => false,
                'color_hex' => '#ffc107',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'EGRESADO',
                'slug' => 'egresado',
                'tipo' => 'ESTUDIANTE', // Específico para personas
                'permite_login' => true,
                'permite_procesos_academicos' => false,
                'color_hex' => '#17a2b8',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'ANULADO',
                'slug' => 'anulado',
                'tipo' => 'OPERATIVO', // Para procesos o registros erróneos
                'permite_login' => false,
                'permite_procesos_academicos' => false,
                'color_hex' => '#dc3545',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estados');
    }
};
