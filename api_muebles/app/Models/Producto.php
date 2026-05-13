<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $fillable = [
        'nombre', 'descripcion', 'precio', 'stock', 
        'materiales', 'dimensiones', 'color_principal', 
        'destacado', 'imagen_principal'
    ];

    public function categorias()
    {
        return $this->belongsToMany(Categoria::class, 'categoria_producto');
    }

    public function galeria()
    {
        return $this->hasOne(Galeria::class);
    }
}
