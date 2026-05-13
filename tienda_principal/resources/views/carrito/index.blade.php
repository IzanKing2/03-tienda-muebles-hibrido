@extends('layouts.app')

@section('title', 'Mi Carrito')

@section('content')
<div class="container py-5">

    <h1 class="h3 fw-bold mb-4"><i class="fas fa-shopping-cart me-2"></i>Mi Carrito</h1>

    @if(count($carrito) === 0)
        <div class="text-center py-5">
            <i class="fas fa-cart-arrow-down fa-4x text-secondary opacity-50 mb-4"></i>
            <h4 class="text-muted">Tu carrito está vacío</h4>
            <p class="text-muted">Explora nuestro catálogo y añade productos que te gusten.</p>
            <a href="{{ route('muebles.index') }}" class="btn btn-dark mt-2">
                <i class="fas fa-couch me-2"></i>Ver catálogo
            </a>
        </div>
    @else
        <div class="row g-4">

            {{-- Tabla de productos --}}
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Producto</th>
                                        <th class="text-center" style="width:130px;">Cantidad</th>
                                        <th class="text-end" style="width:110px;">Precio</th>
                                        <th class="text-end" style="width:110px;">Subtotal</th>
                                        <th style="width:50px;"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($carrito as $item)
                                        <tr>
                                            {{-- Imagen + nombre --}}
                                            <td>
                                                <div class="d-flex align-items-center gap-3">
                                                    @if($item['imagen'])
                                                        <img src="{{ asset('storage/' . $item['imagen']) }}"
                                                             alt="{{ $item['nombre'] }}"
                                                             class="rounded"
                                                             style="width:60px;height:60px;object-fit:cover;">
                                                    @else
                                                        <div class="d-flex align-items-center justify-content-center bg-light rounded"
                                                             style="width:60px;height:60px;">
                                                            <i class="fas fa-couch text-secondary"></i>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <div class="fw-semibold">{{ $item['nombre'] }}</div>
                                                        <div class="small text-muted">{{ number_format($item['precio'], 2) }} € / ud.</div>
                                                    </div>
                                                </div>
                                            </td>

                                            {{-- Actualizar cantidad --}}
                                            <td class="text-center">
                                                <form action="{{ route('carrito.actualizar', $item['id']) }}"
                                                      method="POST" class="d-flex align-items-center gap-1 justify-content-center">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="number" name="cantidad"
                                                           value="{{ $item['cantidad'] }}"
                                                           min="1" max="{{ $item['stock'] }}"
                                                           class="form-control form-control-sm text-center"
                                                           style="width:60px;">
                                                    <button type="submit" class="btn btn-sm btn-outline-secondary" title="Actualizar">
                                                        <i class="fas fa-sync-alt"></i>
                                                    </button>
                                                </form>
                                            </td>

                                            {{-- Precio --}}
                                            <td class="text-end fw-semibold">{{ number_format($item['precio'], 2) }} €</td>

                                            {{-- Subtotal --}}
                                            <td class="text-end fw-bold">
                                                {{ number_format($item['precio'] * $item['cantidad'], 2) }} €
                                            </td>

                                            {{-- Eliminar --}}
                                            <td class="text-end">
                                                <form action="{{ route('carrito.eliminar', $item['id']) }}"
                                                      method="POST"
                                                      onsubmit="return confirm('¿Eliminar este producto del carrito?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="mt-3 d-flex gap-2">
                    <a href="{{ route('muebles.index') }}" class="btn btn-outline-dark btn-sm">
                        <i class="fas fa-arrow-left me-1"></i>Seguir comprando
                    </a>
                    <form action="{{ route('carrito.vaciar') }}" method="POST"
                          onsubmit="return confirm('¿Vaciar todo el carrito?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm">
                            <i class="fas fa-trash-alt me-1"></i>Vaciar carrito
                        </button>
                    </form>
                </div>
            </div>

            {{-- Resumen del pedido --}}
            <div class="col-lg-4">
                <div class="card shadow-sm border-0 sticky-top" style="top: 80px;">
                    <div class="card-header bg-dark text-white fw-semibold">
                        <i class="fas fa-receipt me-2"></i>Resumen del pedido
                    </div>
                    <div class="card-body">
                        @foreach($carrito as $item)
                            <div class="d-flex justify-content-between mb-2 small">
                                <span class="text-truncate me-2" style="max-width:180px;">
                                    {{ $item['nombre'] }} × {{ $item['cantidad'] }}
                                </span>
                                <span class="fw-semibold text-nowrap">
                                    {{ number_format($item['precio'] * $item['cantidad'], 2) }} €
                                </span>
                            </div>
                        @endforeach

                        <hr>

                        <div class="d-flex justify-content-between fw-bold fs-5 mb-4">
                            <span>Total</span>
                            <span>{{ number_format($total, 2) }} €</span>
                        </div>

                        <div class="alert alert-info small p-2 mb-3">
                            <i class="fas fa-info-circle me-1"></i>
                            El proceso de pago estará disponible próximamente.
                        </div>

                        <button class="btn btn-warning w-100 fw-semibold" disabled>
                            <i class="fas fa-credit-card me-2"></i>Proceder al pago
                        </button>
                    </div>
                </div>
            </div>

        </div>
    @endif

</div>
@endsection
