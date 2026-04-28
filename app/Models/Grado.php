<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Grado extends Model
{
    use SoftDeletes;
    protected $table = 'grados';
    protected $fillable = [
        'nombre',
        'orden',
        'ciclo',
        'nivel_id',
        'estado_id'
    ];
    protected $casts = [
        'orden'      => 'integer', // Fundamental para que el ordenamiento sea numérico
        'ciclo'      => 'integer', // Controla el ciclo académico (1: Tronco Común, 2: Especialidad)
        'nivel_id'   => 'integer', // Vincula el grado a un nivel académico específico
        'estado_id'  => 'integer',
        'deleted_at' => 'datetime',
    ];
    public function nivel()
    {
        return $this->belongsTo(Nivel::class);
    }

    public function estado(): BelongsTo
    {
        return $this->belongsTo(Estado::class);
    }
    public function pensums()
    {
        return $this->hasMany(Pensum::class);
    }
    protected static function booted()
    {
        static::addGlobalScope('orden_maestro', function (Builder $builder) {
            $builder->orderBy('nivel_id', 'asc')->orderBy('orden', 'asc');
        });
    }

    public function getStatusBadgeAttribute()
    {
        // Usamos la relación estado para sacar el color dinámico
        $color = $this->estado->color_hex ?? '#6c757d';
        $nombre = strtoupper($this->estado->nombre ?? 'N/A');

        return "<span class='badge shadow-sm' 
                      style='background-color: {$color}; color: #fff; padding: 0.5em 0.8em; font-weight: 600; border-radius: 2px;'>
                    <i class='fas fa-check-circle mr-1' style='font-size: 0.7rem;'></i>
                    {$nombre}
                </span>";
    }

    /**
     * Etiqueta elegante para el Nivel Académico.
     */
    public function getNivelLabelAttribute()
    {
        return "<span class='text-muted' style='font-size: 0.85rem; font-weight: 700;'>
                    <i class='fas fa-layer-group text-primary mr-1'></i> 
                    " . strtoupper($this->nivel->nombre ?? 'SIN NIVEL') . "
                </span>";
    }
}
