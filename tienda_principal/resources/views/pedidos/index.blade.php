@extends('layouts.app')

@section('title', 'Mis Pedidos')

@section('head')
<style>
    .orders-table th {
        font-size: .72rem; text-transform: uppercase; letter-spacing: .5px;
        font-weight: 600; color: #fff; background: var(--color-primary); padding: .85rem 1rem;
    }
    .orders-table td { padding: .85rem 1rem; vertical-align: middle; font-size: .88rem; }
    .orders-table tbody tr { border-bottom: 1px solid var(--color-border); transition: var(--transition); }
    .orders-table tbody tr:hover { background: var(--color-surface); }
    .status-badge {
        font-size: .7rem; font-weight: 600; padding: .25rem .6rem; border-radius: 50px;
    }
</style>
@endsection

@section('content')
<div class="container py-5 animate-in">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h2 class="fw-700 ls-tight mb-0" style="font-size:1.5rem;">
            <i class="fas fa-box me-2" style="color:var(--color-accent);"></i>Mis Pedidos
        </h2>
        <a href="{{ route('muebles.index') }}" class="btn btn-outline-elegant btn-sm">
            <i class="fas fa-shopping-bag me-1"></i>Seguir comprando
        </a>
    </div>

    @if($pedidos->isEmpty())
        <div class="text-center py-5">
            <div style="width:80px;height:80px;border-radius:50%;background:var(--color-surface);display:inline-flex;align-items:center;justify-content:center;margin-bottom:1rem;">
                <i class="fas fa-box-open fa-2x" style="color:var(--color-border);"></i>
            </div>
            <h5 style="color:var(--color-text-muted);font-weight:600;">Aún no tienes pedidos</h5>
            <a href="{{ route('muebles.index') }}" class="btn btn-elegant-dark mt-2">Ver catálogo</a>
        </div>
    @else
        <div style="background:var(--color-card);border:1px solid var(--color-border);border-radius:var(--radius-lg);overflow:hidden;">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 orders-table">
                    <thead>
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
                            $statusStyles = [
                                'pendiente'  => 'background:rgba(201,169,110,.12);color:var(--color-accent);',
                                'pagado'     => 'background:rgba(52,152,219,.1);color:#3498db;',
                                'enviado'    => 'background:rgba(52,73,94,.1);color:#34495e;',
                                'entregado'  => 'background:rgba(45,138,78,.08);color:var(--color-success);',
                                'cancelado'  => 'background:rgba(192,57,43,.08);color:var(--color-danger);',
                            ];
                            $style = $statusStyles[$pedido->estado] ?? 'background:var(--color-surface);color:var(--color-text-muted);';
                        @endphp
                        <tr>
                            <td class="fw-700">#{{ $pedido->id }}</td>
                            <td style="color:var(--color-text-muted);font-size:.82rem;">{{ $pedido->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ $pedido->items_count }} artículo{{ $pedido->items_count !== 1 ? 's' : '' }}</td>
                            <td class="fw-700">{{ number_format($pedido->total, 2) }} €</td>
                            <td><span class="status-badge" style="{{ $style }}">{{ ucfirst($pedido->estado) }}</span></td>
                            <td>
                                <a href="{{ route('pedidos.show', $pedido->id) }}" class="btn btn-sm btn-outline-elegant">
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
