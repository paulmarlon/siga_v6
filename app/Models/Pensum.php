<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pensum extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pensums';
    protected $primaryKey = 'id';
    protected $fillable = [
        'carrera_id',
        'materia_id',
        'grado_id',
        'es_obligatoria',
        'estado_id',
        'deleted_at',
    ];
    // En lugar de $dates, usamos $casts
    protected $casts = [
        'carrera_id'     => 'integer',
        'materia_id'     => 'integer',
        'grado_id'       => 'integer',
        'es_obligatoria' => 'boolean', // Convierte el 1/0 de la DB en true/false de PHP
        'estado_id'      => 'integer',
        'deleted_at'     => 'datetime', // Aquí es donde ahora vive la configuración de fecha
    ];
    public function carrera(): BelongsTo
    {
        return $this->belongsTo(Carrera::class);
    }
    public function materia(): BelongsTo
    {
        return $this->belongsTo(Materia::class);
    }
    public function grado(): BelongsTo
    {
        return $this->belongsTo(Grado::class);
    }
    public function estado(): BelongsTo
    {
        return $this->belongsTo(Estado::class);
    }
    public function getCicloNombreAttribute(): string
    {
        return $this->grado->ciclo === 1 ? 'TRONCO COMÚN' : 'ESPECIALIDAD';
    }
}
