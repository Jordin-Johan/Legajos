<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Documento extends Model
{
    //
    use HasFactory;

    protected $fillable =[
        'titulo_documento',
        'descripcion_documento',
        'archivo_ruta',
        'estado_documento',
        'seccion_id',
        'empleado_id'
    ];

    public function seccion(): BelongsTo
    {
        return $this->belongsTo(Seccion::class, 'seccion_id');
    }
    
    public function empleado(): BelongsTo
    {
        return $this->belongsTo(Empleado::class, 'empleado_id');
    }
}
