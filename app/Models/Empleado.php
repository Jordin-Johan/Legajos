<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $fillable = [
        'image',
        'nombre',
        'apellido',
        'dni',
        'direccion',
        'correo',
        'cargo',
        'varEnlace',
        'tipoPersonal',
    ];

    // protected $casts = [
    //     'tipoPersonal' => 'boolean',
    // ]; 

    public function secciones()
    {
        return $this->hasMany(Seccion::class);
    }

    public function documentos()
    {
        return $this->hasMany(Documento::class);
    }

}

