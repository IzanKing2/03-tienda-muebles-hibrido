@extends('layouts.app')

@section('title', 'Pedido #' . $pedido->id)

@section('head')
<style>
    .order-card {
        background: var(--color-card);
        border: 1px solid var(--color-border);
        border-radius: var(--radius-lg);
        overflow: hidden;
    }
    .order-header {
        background: var(--color-primary);
        color: #fff;
        padding: .85rem 1.2rem;
        font-weight: 600;
        font-size: .88rem;
        display: flex; align-items: center; gap: .5rem;
    }
    .order-header i { color: var(--color-accent); }
    .order-table th {
        font-size: .72rem; text-transform: uppercase; letter-spacing: .5px;
        font-weight: 600; background: var(--color-surface); padding: .75rem 1rem;
        color: var(--color-text-muted);
    }
    .order-table td { padding: .75rem 1rem; font-size: .88rem; }
    .order-table tbody tr { border-bottom: 1px solid var(--color-border); }
    .order-table tfoot td { background: var(--color-surface); }
    .info-label { font-size: .72rem; text-transform: uppercase; letter-spacing: .5px; font-weight: 600; color: var(--color-text-muted); }
    .info-value { font-size: .88rem; }
</style>
@endsection

@section('content')
<div class="container py-5 animate-in">

    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h2 class="fw-700 ls-tight mb-0" style="font-size:1.5rem;">
                Pedido <span style="color:var(--color-text-muted);">#{{ $pedido->id }}</span>
            </h2>
            <small style="color:var(--color-text-muted);font-size:.82rem;">Realizado el {{ $pedido->created_at->format('d/m/Y \a \l\a\s H:i') }}</small>
        </div>
        <a href="{{ route('pedidos.index') }}" class="btn btn-outline-elegant btn-sm">
            <i class="fas fa-arrow-left me-1"></i>Mis pedidos
        </a>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="order-card">
                <div class="order-header"><i class="fas fa-list"></i>Artículos</div>
                <div class="table-responsive">
                    <table class="table align-middle mb-0 order-table">
                        <thead>
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
                                <td class="fw-600">{{ $item->nombre }}</td>
                                <td class="text-center">{{ $item->cantidad }}</td>
                                <td class="text-end" style="color:var(--color-text-muted);">{{ number_format($item->precio, 2) }} €</td>
                                <td class="text-end fw-700">{{ number_format($item->subtotal(), 2) }} €</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-end fw-700">Total del pedido</td>
                                <td class="text-end fw-800" style="font-size:1.1rem;color:var(--color-primary);">{{ number_format($pedido->total, 2) }} €</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            @php
                $statusStyles = [
                    'pendiente'  => 'background:rgba(201,169,110,.12);color:var(--color-accent);',
                    'pagado'     => 'background:rgba(52,152,219,.1);color:#3498db;',
                    'enviado'    => 'background:rgba(52,73,94,.1);color:#34495e;',
                    'entregado'  => 'background:rgba(45,138,78,.08);color:var(--color-success);',
                    'cancelado'  => 'background:rgba(192,57,43,.08);color:var(--color-danger);',
                ];
                $iconMap = [
                    'pendiente'=>'clock', 'pagado'=>'check-circle', 'enviado'=>'truck',
                    'entregado'=>'box', 'cancelado'=>'times-circle',
                ];
                $style = $statusStyles[$pedido->estado] ?? 'background:var(--color-surface);';
                $icon = $iconMap[$pedido->estado] ?? 'circle';
            @endphp

            <div class="order-card mb-3">
                <div class="order-header"><i class="fas fa-info-circle"></i>Estado</div>
                <div class="text-center py-4 px-3">
                    <span style="{{ $style }}font-size:.88rem;font-weight:700;padding:.45rem 1.2rem;border-radius:50px;">
                        <i class="fas fa-{{ $icon }} me-1"></i>{{ ucfirst($pedido->estado) }}
                    </span>
                </div>
            </div>

            <div class="order-card mb-3">
                <div class="order-header"><i class="fas fa-map-marker-alt"></i>Envío</div>
                <div class="p-3">
                    <div class="mb-2"><span class="info-label">Nombre</span><div class="info-value">{{ $pedido->nombre_cliente }}</div></div>
                    <div class="mb-2"><span class="info-label">Email</span><div class="info-value">{{ $pedido->email_cliente }}</div></div>
                    <div class="mb-2"><span class="info-label">Dirección</span><div class="info-value">{{ $pedido->direccion_entrega }}</div></div>
                    @if($pedido->telefono)
                        <div class="mb-2"><span class="info-label">Teléfono</span><div class="info-value">{{ $pedido->telefono }}</div></div>
                    @endif
                    <div><span class="info-label">Pago</span><div class="info-value">{{ ucfirst($pedido->metodo_pago) }}</div></div>
                </div>
            </div>

            @if($pedido->notas)
            <div class="order-card">
                <div class="order-header"><i class="fas fa-sticky-note"></i>Notas</div>
                <div class="p-3" style="font-size:.85rem;color:var(--color-text-muted);">{{ $pedido->notas }}</div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
