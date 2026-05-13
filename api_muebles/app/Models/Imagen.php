<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Imagen extends Model
{
    protected $table = 'imagenes';
    
    protected $fillable = ['galeria_id', 'ruta', 'es_principal', 'orden'];

    public function galeria()
    {
        return $this->belongsTo(Galeria::class);
    }
}
