<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Turno extends Model
{
    use SoftDeletes;

    protected $table = 'turnos';
    protected $fillable = ['nombre'];

    protected static function boot()
    {
        parent::boot();
        // Asegura consistencia de datos antes de guardar
        static::saving(function ($turno) {
            $turno->nombre = mb_convert_case(trim($turno->nombre), MB_CASE_UPPER, "UTF-8");
        });
    }
}
