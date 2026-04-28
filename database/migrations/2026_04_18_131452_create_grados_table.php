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
        Schema::create('grados', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->integer('orden')->default(0);
            $table->tinyInteger('ciclo')->default(1)->comment('1: Tronco Común, 2: Especialidad');
            // Relación con Niveles (Licenciatura, Técnico, etc.)
            $table->foreignId('nivel_id')->constrained('nivels')->onDelete('cascade');
            // Control de Estado
            $table->foreignId('estado_id')->default(1)->constrained('estados');
            $table->softDeletes(); // Imprescindible para auditoría
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grados');
    }
};
