<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Nivel extends Model
{
    use SoftDeletes;

    protected $table = 'nivels';
    protected $fillable = [
        'nombre',
        'slug',
        'estado_id'
    ];

    // RELACIÓN: Un nivel pertenece a un estado
    public function estado()
    {
        return $this->belongsTo(Estado::class, 'estado_id');
    }

    // BOOT: Para automatizar el slug
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($nivel) {
            $nivel->slug = Str::slug($nivel->nombre);
        });
    }
    public function grados()
    {
        return $this->hasMany(Grado::class, 'nivel_id');
    }
    public function carreras()
    {
        return $this->hasMany(Carrera::class, 'nivel_id');
    }
}
