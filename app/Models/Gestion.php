<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Gestion extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'nombre',
        'estado_id'
    ];
    public function estado(): BelongsTo
    {
        return $this->belongsTo(Estado::class, 'estado_id');
    }
    public function configuracion(): HasOne
    {
        return $this->hasOne(Configuracion::class, 'gestion_actual_id');
    }
    public function periodos()
    {
        return $this->hasMany(Periodo::class, 'gestion_id');
    }
}
