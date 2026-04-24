<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    use HasFactory;
    protected $fillable = [
        'nombre',
        'email',
        'sucursal_id',
    ];

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class);
    }
}
