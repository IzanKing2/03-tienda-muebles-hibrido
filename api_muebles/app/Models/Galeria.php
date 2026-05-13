<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Galeria extends Model
{
    protected $fillable = ['producto_id'];

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    public function imagenes()
    {
        return $this->hasMany(Imagen::class);
    }
}
