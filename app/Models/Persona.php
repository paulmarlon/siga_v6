<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Persona extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'personas';

    protected $fillable = [
        'ci',
        'nombres',
        'ap_paterno',
        'ap_materno',
        'fecha_nacimiento',
        'sexo',
        'celular',
        'email_personal',
        'foto_path',
        'direccion_id',
        'estado_id',
    ];

    /**
     * Casts de atributos: 
     * Garantiza que fecha_nacimiento siempre sea un objeto Carbon.
     */
    protected function casts(): array
    {
        return [
            'fecha_nacimiento' => 'date',
        ];
    }

    // --- ACCESORES (Getters) ---

    /**
     * ACCESOR: Nombre Completo
     * Para usar en AdminLTE: {{ $persona->full_name }}
     */
    public function getFullNameAttribute()
    {
        return trim("{$this->nombres} {$this->ap_paterno} {$this->ap_materno}");
    }

    /**
     * ACCESOR: Edad actual
     * Útil para reportes académicos y de salud
     */
    public function getEdadAttribute()
    {
        if (!$this->fecha_nacimiento) return 'N/A';
        return $this->fecha_nacimiento->age;
    }

    /**
     * ACCESOR: Etiqueta de Sexo
     * Para mostrar en tablas: {{ $persona->sexo_label }}
     */
    public function getSexoLabelAttribute()
    {
        $opciones = [
            'M' => 'MASCULINO',
            'F' => 'FEMENINO',
        ];
        return $opciones[$this->sexo] ?? 'OTRO';
    }

    // --- RELACIONES ---

    public function user()
    {
        return $this->hasOne(User::class, 'persona_id');
    }

    public function direccion()
    {
        return $this->belongsTo(Direccion::class, 'direccion_id')->withTrashed();
    }

    public function estado()
    {
        return $this->belongsTo(Estado::class, 'estado_id');
    }

    // --- MUTADORES (Setters) ---
    // Limpian espacios extras y convierten a MAYÚSCULAS automáticamente

    public function setNombresAttribute($value)
    {
        $this->attributes['nombres'] = mb_strtoupper(trim($value));
    }

    public function setApPaternoAttribute($value)
    {
        $this->attributes['ap_paterno'] = mb_strtoupper(trim($value));
    }

    public function setApMaternoAttribute($value)
    {
        $this->attributes['ap_materno'] = mb_strtoupper(trim($value));
    }
}
