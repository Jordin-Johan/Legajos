<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Seccion extends Model
{
    //
    use HasFactory;
    protected $fillable = [
        'nombre_seccion',
        'descripcion',
        
    ];
    
    public function empleado(){
        return $this->belongsTo(Empleado::class);
    }

    public function documentos(){
        return $this->hasMany(Documento::class);
    }
}
