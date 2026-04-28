<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Direccion extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'direccions';
    protected $fillable = [
        'pais',
        'departamento',
        'provincia',
        'ciudad',
        'zona',
        'detalle',
    ];
    protected $casts = [
        'detalle' => 'array', // Laravel lo decodifica solo
    ];
    public function configuracion(): HasOne
    {
        return $this->hasOne(Configuracion::class, 'direccion_id');
    }
    protected $dates = ['deleted_at'];
    public function personas(): HasMany
    {
        // Asegúrate de que 'Persona' sea el nombre correcto de tu modelo
        // y que en la tabla 'personas' exista la columna 'direccion_id'
        return $this->hasMany(Persona::class, 'direccion_id');
    }
}
