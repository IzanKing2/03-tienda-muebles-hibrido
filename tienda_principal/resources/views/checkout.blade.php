@extends('layouts.app')

@section('title', 'Finalizar compra')

@section('head')
<style>
    .checkout-card {
        background: var(--color-card);
        border: 1px solid var(--color-border);
        border-radius: var(--radius-lg);
        overflow: hidden;
    }
    .checkout-header {
        background: var(--color-primary);
        color: #fff;
        padding: .85rem 1.2rem;
        font-weight: 600;
        font-size: .88rem;
        display: flex; align-items: center; gap: .5rem;
    }
    .checkout-header i { color: var(--color-accent); }
    .checkout-body { padding: 1.5rem; }
    .checkout-body .form-control,
    .checkout-body .form-select,
    .checkout-body textarea {
        border: 1.5px solid var(--color-border);
        border-radius: var(--radius-sm);
        font-size: .88rem;
        transition: var(--transition);
    }
    .checkout-body .form-control:focus,
    .checkout-body .form-select:focus,
    .checkout-body textarea:focus {
        border-color: var(--color-accent);
        box-shadow: 0 0 0 3px rgba(201,169,110,.1);
    }
</style>
@endsection

@section('content')
<div class="container py-5 animate-in">
    <h2 class="fw-700 ls-tight mb-4" style="font-size:1.5rem;">
        <i class="fas fa-credit-card me-2" style="color:var(--color-accent);"></i>Finalizar compra
    </h2>

    <form action="{{ route('checkout.store') }}" method="POST">
        @csrf
        <div class="row g-4">
            <div class="col-lg-7">
                <div class="checkout-card">
                    <div class="checkout-header">
                        <i class="fas fa-map-marker-alt"></i>Datos de envío y pago
                    </div>
                    <div class="checkout-body">
                        @if($errors->any())
                            <div class="alert py-2 mb-3" style="font-size:.82rem;background:rgba(192,57,43,.06);color:var(--color-danger);border-left:3px solid var(--color-danger);border-radius:var(--radius-sm);">
                                <ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                            </div>
                        @endif

                        <div class="row g-3">
                            <div class="col-sm-6">
                                <label class="form-label fw-600" style="font-size:.82rem;">Nombre completo *</label>
                                <input type="text" name="nombre_cliente" class="form-control"
                                       value="{{ old('nombre_cliente', ($usuario['nombre'] ?? '') . ' ' . ($usuario['apellidos'] ?? '')) }}" required>
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label fw-600" style="font-size:.82rem;">Email *</label>
                                <input type="email" name="email_cliente" class="form-control"
                                       value="{{ old('email_cliente', $usuario['email'] ?? '') }}" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-600" style="font-size:.82rem;">Dirección de entrega *</label>
                                <input type="text" name="direccion_entrega" class="form-control"
                                       placeholder="Calle, número, piso, ciudad, código postal"
                                       value="{{ old('direccion_entrega') }}" required>
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label fw-600" style="font-size:.82rem;">Teléfono</label>
                                <input type="tel" name="telefono" class="form-control"
                                       placeholder="+34 600 000 000" value="{{ old('telefono') }}">
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label fw-600" style="font-size:.82rem;">Método de pago *</label>
                                <select name="metodo_pago" class="form-select" required>
                                    <option value="tarjeta" {{ old('metodo_pago','tarjeta') === 'tarjeta' ? 'selected':'' }}>Tarjeta de crédito/débito</option>
                                    <option value="transferencia" {{ old('metodo_pago') === 'transferencia' ? 'selected':'' }}>Transferencia bancaria</option>
                                    <option value="efectivo" {{ old('metodo_pago') === 'efectivo' ? 'selected':'' }}>Pago contra reembolso</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-600" style="font-size:.82rem;">Notas del pedido</label>
                                <textarea name="notas" class="form-control" rows="2"
                                          placeholder="Instrucciones especiales…">{{ old('notas') }}</textarea>
                            </div>
                        </div>

                        <div class="mt-4 p-3 d-flex align-items-center gap-2" style="background:rgba(201,169,110,.06);border-radius:var(--radius-sm);border:1px solid rgba(201,169,110,.15);font-size:.82rem;color:var(--color-accent);">
                            <i class="fas fa-info-circle"></i>
                            <span><strong>Compra simulada</strong> — No se realizará ningún cargo real.</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="checkout-card">
                    <div class="checkout-header">
                        <i class="fas fa-shopping-bag"></i>Resumen del pedido
                    </div>
                    <ul class="list-group list-group-flush">
                        @foreach($carrito->items as $item)
                        <li class="list-group-item d-flex justify-content-between align-items-center py-3 px-4" style="border-color:var(--color-border);">
                            <div>
                                <div class="fw-600" style="font-size:.88rem;">{{ $item->nombre }}</div>
                                <small style="color:var(--color-text-muted);font-size:.78rem;">{{ number_format($item->precio, 2) }} € × {{ $item->cantidad }}</small>
                            </div>
                            <span class="fw-700" style="font-size:.9rem;">{{ number_format($item->subtotal(), 2) }} €</span>
                        </li>
                        @endforeach
                    </ul>
                    <div class="p-4" style="background:var(--color-surface);">
                        <div class="d-flex justify-content-between align-items-center mb-1" style="font-size:.85rem;">
                            <span style="color:var(--color-text-muted);">Subtotal</span>
                            <span>{{ number_format($carrito->total(), 2) }} €</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3" style="font-size:.85rem;">
                            <span style="color:var(--color-text-muted);">Envío</span>
                            <span class="fw-600" style="color:var(--color-success);">Gratis</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center fw-700 pt-3" style="font-size:1.2rem;border-top:1px solid var(--color-border);">
                            <span>Total</span>
                            <span style="color:var(--color-primary);">{{ number_format($carrito->total(), 2) }} €</span>
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-2 mt-3">
                    <button type="submit" class="btn btn-elegant-dark btn-lg">
                        <i class="fas fa-check-circle me-2"></i>Confirmar pedido
                    </button>
                    <a href="{{ route('carrito.index') }}" class="btn btn-outline-elegant">
                        <i class="fas fa-arrow-left me-1"></i>Volver al carrito
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
