<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Periodo extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'periodos';
    protected $fillable = [
        'nombre',
        'gestion_id',
        'fecha_inicio',
        'fecha_fin',
        'estado_id',
    ];
    protected $casts = ['fecha_inicio' => 'date', 'fecha_fin' => 'date'];
    public function gestion(): BelongsTo
    {
        return $this->belongsTo(Gestion::class, 'gestion_id');
    }
    public function estado(): BelongsTo
    {
        return $this->belongsTo(Estado::class, 'estado_id');
    }
}
