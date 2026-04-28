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
        Schema::create('configuracions', function (Blueprint $table) {
            $table->id();

            // Datos de la Institución
            $table->string('nombre_institucion');
            $table->string('sigla_institucion', 20)->nullable();
            $table->string('nit', 30)->nullable();

            // Contacto y Web
            $table->string('telefono', 50)->nullable();
            $table->string('email_contacto', 100)->nullable();
            $table->string('web', 150)->nullable();

            // Identidad Visual y Sistema
            $table->string('logo_path')->nullable();
            $table->string('divisa', 10)->default('BOB'); // Bolivianos por defecto

            // Relaciones
            // Relación con Dirección (Vínculo que pediste)
            $table->foreignId('direccion_id')
                ->nullable()
                ->constrained('direccions')
                ->onDelete('set null');

            // Relación con Gestión (Asumiendo que tienes una tabla 'gestions')
            $table->foreignId('gestion_actual_id')
                ->nullable()
                ->constrained('gestions')
                ->onDelete('set null');

            $table->timestamps();

            // Nota: Para asegurar que solo exista UNA configuración en el sistema
            // podrías añadir un comentario o lógica en el controlador para evitar múltiples filas.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('configuracions');
    }
};
