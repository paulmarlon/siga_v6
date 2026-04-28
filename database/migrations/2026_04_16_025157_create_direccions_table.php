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
        Schema::create('direccions', function (Blueprint $table) {
            $table->id();
            $table->string('pais', 50)->default('Bolivia')->nullable();
            $table->string('departamento', 30)->default('La Paz')->nullable();
            $table->string('provincia', 100)->nullable(); // Opcional
            $table->string('ciudad', 100)->nullable();    // Opcional
            $table->string('zona', 100)->nullable();      // Opcional
            $table->text('detalle')->nullable(); // Opcional por defecto
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('direccions');
    }
};
