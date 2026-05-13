@extends('layouts.app')

@section('title', 'Finalizar compra')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-12">
            <h2 class="fw-bold mb-4"><i class="fas fa-credit-card me-2"></i>Finalizar compra</h2>
        </div>
    </div>

    <form action="{{ route('checkout.store') }}" method="POST">
        @csrf
        <div class="row g-4">

            {{-- ══ Datos de envío ══ --}}
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-dark text-white fw-semibold">
                        <i class="fas fa-map-marker-alt me-2"></i>Datos de envío y pago
                    </div>
                    <div class="card-body">

                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $e)
                                        <li>{{ $e }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="row g-3">
                            <div class="col-sm-6">
                                <label class="form-label fw-semibold">Nombre completo *</label>
                                <input type="text" name="nombre_cliente" class="form-control"
                                       value="{{ old('nombre_cliente', ($usuario['nombre'] ?? '') . ' ' . ($usuario['apellidos'] ?? '')) }}"
                                       required>
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label fw-semibold">Email *</label>
                                <input type="email" name="email_cliente" class="form-control"
                                       value="{{ old('email_cliente', $usuario['email'] ?? '') }}"
                                       required>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Dirección de entrega *</label>
                                <input type="text" name="direccion_entrega" class="form-control"
                                       placeholder="Calle, número, piso, ciudad, código postal"
                                       value="{{ old('direccion_entrega') }}" required>
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label fw-semibold">Teléfono</label>
                                <input type="tel" name="telefono" class="form-control"
                                       placeholder="+34 600 000 000"
                                       value="{{ old('telefono') }}">
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label fw-semibold">Método de pago *</label>
                                <select name="metodo_pago" class="form-select" required>
                                    <option value="tarjeta"      {{ old('metodo_pago','tarjeta') === 'tarjeta'      ? 'selected':'' }}>
                                        Tarjeta de crédito/débito
                                    </option>
                                    <option value="transferencia" {{ old('metodo_pago') === 'transferencia' ? 'selected':'' }}>
                                        Transferencia bancaria
                                    </option>
                                    <option value="efectivo"     {{ old('metodo_pago') === 'efectivo'     ? 'selected':'' }}>
                                        Pago contra reembolso
                                    </option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Notas del pedido</label>
                                <textarea name="notas" class="form-control" rows="2"
                                          placeholder="Instrucciones especiales de entrega…">{{ old('notas') }}</textarea>
                            </div>
                        </div>

                        {{-- Aviso pago simulado --}}
                        <div class="alert alert-info mt-4 mb-0">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Compra simulada</strong> — No se realizará ningún cargo real.
                            Este es un entorno de demostración.
                        </div>
                    </div>
                </div>
            </div>

            {{-- ══ Resumen del pedido ══ --}}
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-dark text-white fw-semibold">
                        <i class="fas fa-shopping-bag me-2"></i>Resumen del pedido
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            @foreach($carrito->items as $item)
                            <li class="list-group-item d-flex justify-content-between align-items-center py-3 px-4">
                                <div>
                                    <div class="fw-semibold">{{ $item->nombre }}</div>
                                    <small class="text-muted">
                                        {{ number_format($item->precio, 2) }} € × {{ $item->cantidad }}
                                    </small>
                                </div>
                                <span class="fw-bold">{{ number_format($item->subtotal(), 2) }} €</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="card-footer bg-light">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="text-muted">Subtotal</span>
                            <span>{{ number_format($carrito->total(), 2) }} €</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-muted">Envío</span>
                            <span class="text-success fw-semibold">Gratis</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center fw-bold fs-5 border-top pt-3">
                            <span>Total</span>
                            <span class="text-dark">{{ number_format($carrito->total(), 2) }} €</span>
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-2 mt-3">
                    <button type="submit" class="btn btn-dark btn-lg">
                        <i class="fas fa-check-circle me-2"></i>Confirmar pedido
                    </button>
                    <a href="{{ route('carrito.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Volver al carrito
                    </a>
                </div>
            </div>

        </div>
    </form>
</div>
@endsection
