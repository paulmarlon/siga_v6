<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paralelo extends Model
{
    protected $table = 'paralelos';
    protected $fillable = ['nombre'];

    // Relación inversa: Un paralelo puede estar en muchas ofertas
}
