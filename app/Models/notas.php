<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class notas extends Model
{
    use HasFactory;

    protected $fillable = [
        'comentario',
        'proximo_seguimiento',
        'estados',
        'resultado'
        ,'users',
        'clientes'
    ];
}
