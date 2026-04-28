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
        Schema::create('personas', function (Blueprint $table) {
            $table->id();
            $table->string('ci', 15)->unique(); // Obligatorio y único
            $table->string('nombres', 100);
            $table->string('ap_paterno', 50)->nullable();
            $table->string('ap_materno', 50)->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->char('sexo', 1)->comment('M, F');
            $table->string('celular', 20)->nullable();
            $table->string('email_personal', 100)->unique(); // Obligatorio para el vínculo
            $table->string('foto_path')->nullable();
            $table->tinyInteger('estado_id')->default(1)->comment('1: Activo, 0: Inactivo');
            $table->foreignId('direccion_id')->nullable()->constrained('direccions')->onDelete('set null');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personas');
    }
};
