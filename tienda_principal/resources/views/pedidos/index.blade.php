@extends('layouts.app')

@section('title', 'Mis Pedidos')

@section('content')
<div class="container py-5">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h2 class="fw-bold mb-0"><i class="fas fa-box me-2"></i>Mis Pedidos</h2>
        <a href="{{ route('muebles.index') }}" class="btn btn-outline-dark btn-sm">
            <i class="fas fa-shopping-bag me-1"></i>Seguir comprando
        </a>
    </div>

    @if($pedidos->isEmpty())
        <div class="text-center py-5">
            <i class="fas fa-box-open fa-4x text-secondary opacity-50 mb-3"></i>
            <h5 class="text-muted">Aún no tienes pedidos</h5>
            <a href="{{ route('muebles.index') }}" class="btn btn-dark mt-2">
                Ver catálogo
            </a>
        </div>
    @else
        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>#Pedido</th>
                            <th>Fecha</th>
                            <th>Artículos</th>
                            <th>Total</th>
                            <th>Estado</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pedidos as $pedido)
                        @php
                            $badgeMap = [
                                'pendiente'  => 'warning text-dark',
                                'pagado'     => 'info text-dark',
                                'enviado'    => 'primary',
                                'entregado'  => 'success',
                                'cancelado'  => 'danger',
                            ];
                            $badge = $badgeMap[$pedido->estado] ?? 'secondary';
                        @endphp
                        <tr>
                            <td class="fw-bold">#{{ $pedido->id }}</td>
                            <td class="text-muted small">{{ $pedido->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ $pedido->items_count }} artículo{{ $pedido->items_count !== 1 ? 's' : '' }}</td>
                            <td class="fw-semibold">{{ number_format($pedido->total, 2) }} €</td>
                            <td>
                                <span class="badge bg-{{ $badge }}">
                                    {{ ucfirst($pedido->estado) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('pedidos.show', $pedido->id) }}"
                                   class="btn btn-sm btn-outline-dark">
                                    <i class="fas fa-eye me-1"></i>Ver
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
@endsection
