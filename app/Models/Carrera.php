<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Model;

class Carrera extends Model
{
    use SoftDeletes, HasFactory;
    protected $table = 'carreras';
    protected $fillable = [
        'carrera_base_id',
        'nombre',
        'sigla',
        'resolucion',
        'duracion',
        'titulo',
        'nivel_id',
        'estado_id',
        'es_tronco_comun',
    ];
    protected $casts = [
        'carrera_base_id' => 'integer',
        'nivel_id'        => 'integer',
        'estado_id'       => 'integer',
        'es_tronco_comun' => 'boolean', // Convierte 1/0 a true/false
        'duracion'        => 'integer', // Asumiendo que guardas cantidad de semestres/años
        'deleted_at'      => 'datetime',
    ];
    public function carreraBase()
    {
        return $this->belongsTo(Carrera::class, 'carrera_base_id');
    }
    public function especialidades()
    {
        return $this->hasMany(Carrera::class, 'carrera_base_id');
    }
    public function nivel()
    {
        return $this->belongsTo(Nivel::class);
    }
    public function pensums()
    {
        return $this->hasMany(Pensum::class);
    }
    public function estado()
    {
        return $this->belongsTo(Estado::class);
    }
    // --- ACCESSORS (LÓGICA DE NEGOCIO) ---

    /**
     * Determina si la carrera es una especialidad (tiene una base)
     * Uso: $carrera->es_especialidad (devuelve true/false)
     */
    public function getEsEspecialidadAttribute()
    {
        return $this->carrera_base_id !== null;
    }

    /**
     * Obtiene el nombre de la carrera base o un texto descriptivo
     */
    public function getNombreBaseAttribute()
    {
        return $this->carreraBase ? $this->carreraBase->nombre : 'Sin Carrera Base (Tronco Común)';
    }
    // En el modelo Carrera.php
    public function materiasMalla()
    {
        $ids = [$this->id];
        if ($this->carrera_base_id) {
            $ids[] = $this->carrera_base_id;
        }

        return Pensum::whereIn('carrera_id', $ids)
            ->with(['materia', 'grado'])
            ->get();
    }
}
