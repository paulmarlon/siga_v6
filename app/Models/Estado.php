<?php

namespace App\Models; // Asegúrate de que sea este namespace

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Estado extends Model
{
    use HasFactory;

    // Nombre de la tabla en plural
    protected $table = 'estados';

    // Campos que permitimos llenar (Mass Assignment)
    protected $fillable = [
        'nombre',
        'slug',
        'tipo',
        'permite_login',
        'permite_procesos_academicos',
        'color_hex'
    ];

    /**
     * Relación: Un estado lo pueden tener muchos usuarios.
     * Esto te permite hacer: $estado->users
     */
    protected $casts = [
        'permite_login' => 'boolean',
        'permite_procesos_academicos' => 'boolean',
    ];
    public function grados()
    {
        return $this->hasMany(Grado::class, 'estado_id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'estado_id');
    }


    public function gestiones(): HasMany
    {
        return $this->hasMany(Gestion::class, 'estado_id');
    }
    public function niveles(): HasMany
    {
        return $this->hasMany(Nivel::class, 'estado_id');
    }
    public function carreras(): HasMany
    {
        return $this->hasMany(Carrera::class, 'estado_id');
    }
    public function materias(): HasMany
    {
        return $this->hasMany(Materia::class, 'estado_id');
    }
    /**
     * Scope para buscar por slug (limpia el código en el controlador)
     * Ejemplo: Estado::active()->first();
     */
    public function scopeActive($query)
    {
        return $query->where('slug', 'activo')->where('tipo', 'GLOBAL');
    }
    // --- EL TOQUE DE COLOR DEL SIG@ ---

    /**
     * Muestra el badge con el color real de la DB
     * {!! $estado->badge !!}
     */
    public function getBadgeAttribute()
    {
        return "<span class='badge shadow-sm' 
                      style='background-color: {$this->color_hex}; color: #fff; padding: 0.5em 1em; font-weight: 600;'>
                    <i class='fas fa-tag mr-1' style='font-size: 0.7rem;'></i> 
                    " . strtoupper($this->nombre) . "
                </span>";
    }

    /**
     * Muestra un icono de semáforo según permisos
     */
    public function getIconoPermisoAttribute()
    {
        $color = $this->permite_procesos_academicos ? 'text-success' : 'text-danger';
        $icon = $this->permite_procesos_academicos ? 'fa-check-circle' : 'fa-times-circle';

        return "<i class='fas {$icon} {$color}' title='Procesos Académicos'></i>";
    }
    public function periodos(): HasMany
    {
        return $this->hasMany(Periodo::class, 'estado_id');
    }
}
