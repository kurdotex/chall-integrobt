<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sucursal extends Model
{
    use HasFactory;
    protected $table = 'sucursales';

    protected $fillable = [
        'nombre',
        'ciudad',
        'pais',
        'latitud',
        'longitud',
    ];

    public function empleados()
    {
        return $this->hasMany(Empleado::class);
    }
}
