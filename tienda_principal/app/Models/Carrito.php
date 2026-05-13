<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Carrito extends Model
{
    protected $fillable = ['usuario_id'];

    public function items(): HasMany
    {
        return $this->hasMany(CarritoItem::class);
    }

    public function total(): float
    {
        return $this->items->sum(fn(CarritoItem $i) => $i->precio * $i->cantidad);
    }
}
