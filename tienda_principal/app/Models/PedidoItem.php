<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PedidoItem extends Model
{
    protected $fillable = ['pedido_id', 'producto_id', 'nombre', 'precio', 'cantidad'];

    protected $casts = ['precio' => 'float', 'cantidad' => 'integer'];

    public function pedido(): BelongsTo
    {
        return $this->belongsTo(Pedido::class);
    }

    public function subtotal(): float
    {
        return $this->precio * $this->cantidad;
    }
}
