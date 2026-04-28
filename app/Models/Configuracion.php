<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage; // Importante para el logo

class Configuracion extends Model
{
    // 1. Forzamos el nombre de la tabla en plural (como en tu migración)
    protected $table = 'configuracions';

    // 2. Definimos los campos que se pueden llenar (Mass Assignment)
    protected $fillable = [
        'nombre_institucion',
        'sigla_institucion',
        'nit',
        'direccion_id',
        'telefono',
        'email_contacto',
        'web',
        'logo_path',
        'divisa',
        'gestion_actual_id'
    ];

    /**
     * RELACIÓN: La configuración pertenece a una dirección física.
     */
    public function direccion(): BelongsTo
    {
        return $this->belongsTo(Direccion::class, 'direccion_id');
    }

    /**
     * RELACIÓN: La configuración define qué gestión académica está activa.
     */
    public function gestionActual(): BelongsTo
    {
        return $this->belongsTo(Gestion::class, 'gestion_actual_id');
    }

    /**
     * ACCESOR: Para obtener la URL del logo de forma automática.
     * Uso en Blade: <img src="{{ $config->logo_url }}">
     */
    public function getLogoUrlAttribute()
    {
        if ($this->logo_path && Storage::disk('public')->exists($this->logo_path)) {
            return asset('storage/' . $this->logo_path);
        }
        // Logo por defecto de AdminLTE si no hay uno subido
        return asset('vendor/adminlte/dist/img/AdminLTELogo.png');
    }
}
