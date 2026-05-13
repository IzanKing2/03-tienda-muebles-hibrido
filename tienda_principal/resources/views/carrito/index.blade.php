@extends('layouts.app')

@section('title', 'Mi Carrito')

@section('head')
<style>
    .cart-table th {
        font-size: .72rem;
        text-transform: uppercase;
        letter-spacing: .5px;
        font-weight: 600;
        color: #fff;
        background: var(--color-primary);
        padding: .85rem 1rem;
    }
    .cart-table td { padding: .85rem 1rem; vertical-align: middle; }
    .cart-table tbody tr { border-bottom: 1px solid var(--color-border); transition: var(--transition); }
    .cart-table tbody tr:hover { background: var(--color-surface); }
    .cart-img {
        width: 56px; height: 56px;
        object-fit: cover;
        border-radius: var(--radius-sm);
        border: 1px solid var(--color-border);
    }
    .summary-card {
        background: var(--color-card);
        border: 1px solid var(--color-border);
        border-radius: var(--radius-lg);
        overflow: hidden;
    }
</style>
@endsection

@section('content')
<div class="container py-5 animate-in">

    <h1 class="fw-700 ls-tight mb-4" style="font-size:1.5rem;">
        <i class="fas fa-shopping-bag me-2" style="color:var(--color-accent);"></i>Mi Carrito
    </h1>

    @if($carrito->items->isEmpty())
        <div class="text-center py-5">
            <div style="width:80px;height:80px;border-radius:50%;background:var(--color-surface);display:inline-flex;align-items:center;justify-content:center;margin-bottom:1rem;">
                <i class="fas fa-shopping-bag fa-2x" style="color:var(--color-border);"></i>
            </div>
            <h4 style="color:var(--color-text-muted);font-weight:600;">Tu carrito está vacío</h4>
            <p class="text-muted" style="font-size:.88rem;">Explora nuestro catálogo y añade productos.</p>
            <a href="{{ route('muebles.index') }}" class="btn btn-elegant-dark mt-2">
                <i class="fas fa-couch me-2"></i>Ver catálogo
            </a>
        </div>
    @else
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="summary-card">
                    <div class="table-responsive">
                        <table class="table mb-0 cart-table">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th class="text-center" style="width:130px;">Cantidad</th>
                                    <th class="text-end" style="width:100px;">Precio</th>
                                    <th class="text-end" style="width:100px;">Subtotal</th>
                                    <th style="width:44px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($carrito->items as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                @if($item->imagen)
                                                    <img src="{{ asset('storage/' . $item->imagen) }}" alt="{{ $item->nombre }}" class="cart-img">
                                                @else
                                                    <div class="cart-img d-flex align-items-center justify-content-center" style="background:var(--color-surface);">
                                                        <i class="fas fa-couch" style="color:var(--color-border);"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <div class="fw-600" style="font-size:.88rem;">{{ $item->nombre }}</div>
                                                    <div style="font-size:.75rem;color:var(--color-text-muted);">{{ number_format($item->precio, 2) }} € / ud.</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <form action="{{ route('carrito.actualizar', $item->id) }}" method="POST" class="d-flex align-items-center gap-1 justify-content-center">
                                                @csrf @method('PATCH')
                                                <input type="number" name="cantidad" value="{{ $item->cantidad }}" min="1" max="99"
                                                       class="form-control form-control-sm text-center" style="width:56px;border:1.5px solid var(--color-border);border-radius:var(--radius-sm);font-size:.82rem;">
                                                <button type="submit" class="btn btn-sm btn-outline-elegant" title="Actualizar" style="padding:.2rem .4rem;">
                                                    <i class="fas fa-sync-alt" style="font-size:.65rem;"></i>
                                                </button>
                                            </form>
                                        </td>
                                        <td class="text-end fw-500" style="font-size:.88rem;">{{ number_format($item->precio, 2) }} €</td>
                                        <td class="text-end fw-700" style="font-size:.92rem;">{{ number_format($item->subtotal(), 2) }} €</td>
                                        <td class="text-end">
                                            <form action="{{ route('carrito.eliminar', $item->id) }}" method="POST"
                                                  onsubmit="return confirm('¿Eliminar este producto?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm" title="Eliminar" style="color:var(--color-danger);padding:.2rem .4rem;">
                                                    <i class="fas fa-trash" style="font-size:.75rem;"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mt-3 d-flex gap-2">
                    <a href="{{ route('muebles.index') }}" class="btn btn-outline-elegant btn-sm">
                        <i class="fas fa-arrow-left me-1"></i>Seguir comprando
                    </a>
                    <form action="{{ route('carrito.vaciar') }}" method="POST" onsubmit="return confirm('¿Vaciar todo el carrito?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm" style="color:var(--color-danger);border:1.5px solid rgba(192,57,43,.2);border-radius:var(--radius-sm);font-size:.82rem;">
                            <i class="fas fa-trash-alt me-1"></i>Vaciar carrito
                        </button>
                    </form>
                </div>
            </div>

            {{-- Resumen --}}
            <div class="col-lg-4">
                <div class="summary-card sticky-top" style="top:80px;">
                    <div style="background:var(--color-primary);color:#fff;padding:.85rem 1.2rem;font-weight:600;font-size:.88rem;">
                        <i class="fas fa-receipt me-2" style="color:var(--color-accent);"></i>Resumen
                    </div>
                    <div class="p-3">
                        @foreach($carrito->items as $item)
                            <div class="d-flex justify-content-between mb-2" style="font-size:.82rem;">
                                <span class="text-truncate me-2" style="max-width:170px;color:var(--color-text-muted);">{{ $item->nombre }} × {{ $item->cantidad }}</span>
                                <span class="fw-600 text-nowrap">{{ number_format($item->subtotal(), 2) }} €</span>
                            </div>
                        @endforeach
                        <hr style="border-color:var(--color-border);">
                        <div class="d-flex justify-content-between fw-700 mb-4" style="font-size:1.2rem;">
                            <span>Total</span>
                            <span style="color:var(--color-primary);">{{ number_format($total, 2) }} €</span>
                        </div>
                        <a href="{{ route('checkout') }}" class="btn btn-accent w-100 fw-600">
                            <i class="fas fa-credit-card me-2"></i>Proceder al pago
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
