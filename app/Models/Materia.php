<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Materia extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'materias';
    protected $fillable = [
        'sigla',
        'nombre',
        'horas_academicas',
        'tipo_materia',
        'es_comun',
        'estado_id',
    ];
    protected $casts = [
        'horas_academicas' => 'integer',
        'es_comun'         => 'boolean', // Crucial para tu lógica de "Tronco Común"
        'estado_id'        => 'integer',
        'deleted_at'       => 'datetime',
    ];

    // Relación con el estado
    public function estado()
    {
        return $this->belongsTo(Estado::class);
    }
    public function pensums(): HasMany
    {
        return $this->hasMany(Pensum::class);
    }
}
