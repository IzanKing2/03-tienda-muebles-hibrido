@extends('layouts.app')

@section('title', 'Pedido #' . $pedido->id)

@section('content')
<div class="container py-5">

    {{-- Cabecera --}}
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h2 class="fw-bold mb-0">Pedido <span class="text-muted">#{{ $pedido->id }}</span></h2>
            <small class="text-muted">Realizado el {{ $pedido->created_at->format('d/m/Y \a \l\a\s H:i') }}</small>
        </div>
        <a href="{{ route('pedidos.index') }}" class="btn btn-outline-dark btn-sm">
            <i class="fas fa-arrow-left me-1"></i>Mis pedidos
        </a>
    </div>

    <div class="row g-4">

        {{-- ══ Artículos del pedido ══ --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-dark text-white fw-semibold">
                    <i class="fas fa-list me-2"></i>Artículos
                </div>
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Producto</th>
                                <th class="text-center">Cantidad</th>
                                <th class="text-end">Precio unit.</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pedido->items as $item)
                            <tr>
                                <td class="fw-semibold">{{ $item->nombre }}</td>
                                <td class="text-center">{{ $item->cantidad }}</td>
                                <td class="text-end text-muted">{{ number_format($item->precio, 2) }} €</td>
                                <td class="text-end fw-bold">{{ number_format($item->subtotal(), 2) }} €</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <td colspan="3" class="text-end fw-bold">Total del pedido</td>
                                <td class="text-end fw-bold fs-5 text-dark">{{ number_format($pedido->total, 2) }} €</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        {{-- ══ Info del pedido ══ --}}
        <div class="col-lg-4">

            {{-- Estado --}}
            @php
                $badgeMap = [
                    'pendiente'  => 'warning text-dark',
                    'pagado'     => 'info text-dark',
                    'enviado'    => 'primary',
                    'entregado'  => 'success',
                    'cancelado'  => 'danger',
                ];
                $iconMap = [
                    'pendiente'  => 'clock',
                    'pagado'     => 'check-circle',
                    'enviado'    => 'truck',
                    'entregado'  => 'box',
                    'cancelado'  => 'times-circle',
                ];
                $badge = $badgeMap[$pedido->estado] ?? 'secondary';
                $icon  = $iconMap[$pedido->estado]  ?? 'circle';
            @endphp
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-dark text-white fw-semibold">
                    <i class="fas fa-info-circle me-2"></i>Estado del pedido
                </div>
                <div class="card-body text-center py-4">
                    <span class="badge bg-{{ $badge }} fs-6 px-4 py-2">
                        <i class="fas fa-{{ $icon }} me-2"></i>{{ ucfirst($pedido->estado) }}
                    </span>
                </div>
            </div>

            {{-- Datos de envío --}}
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-dark text-white fw-semibold">
                    <i class="fas fa-map-marker-alt me-2"></i>Envío
                </div>
                <div class="card-body">
                    <dl class="row mb-0 small">
                        <dt class="col-sm-4 text-muted">Nombre</dt>
                        <dd class="col-sm-8">{{ $pedido->nombre_cliente }}</dd>

                        <dt class="col-sm-4 text-muted">Email</dt>
                        <dd class="col-sm-8">{{ $pedido->email_cliente }}</dd>

                        <dt class="col-sm-4 text-muted">Dirección</dt>
                        <dd class="col-sm-8">{{ $pedido->direccion_entrega }}</dd>

                        @if($pedido->telefono)
                        <dt class="col-sm-4 text-muted">Teléfono</dt>
                        <dd class="col-sm-8">{{ $pedido->telefono }}</dd>
                        @endif

                        <dt class="col-sm-4 text-muted">Pago</dt>
                        <dd class="col-sm-8">{{ ucfirst($pedido->metodo_pago) }}</dd>
                    </dl>
                </div>
            </div>

            @if($pedido->notas)
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-dark text-white fw-semibold">
                    <i class="fas fa-sticky-note me-2"></i>Notas
                </div>
                <div class="card-body small">{{ $pedido->notas }}</div>
            </div>
            @endif

        </div>
    </div>
</div>
@endsection
