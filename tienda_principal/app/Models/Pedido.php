<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pedido extends Model
{
    const ESTADOS = ['pendiente', 'pagado', 'enviado', 'entregado', 'cancelado'];

    protected $fillable = [
        'usuario_id', 'estado', 'total',
        'nombre_cliente', 'email_cliente', 'direccion_entrega',
        'telefono', 'metodo_pago', 'notas',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(PedidoItem::class);
    }
}
