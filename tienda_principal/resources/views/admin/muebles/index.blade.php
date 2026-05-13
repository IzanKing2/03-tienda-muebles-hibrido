@extends('layouts.app')

@section('title', 'Gestión de Muebles')

@section('head')
<style>
    .admin-table th {
        font-size: .72rem; text-transform: uppercase; letter-spacing: .5px;
        font-weight: 600; color: #fff; background: var(--color-primary); padding: .85rem 1rem;
    }
    .admin-table td { padding: .75rem 1rem; vertical-align: middle; font-size: .85rem; }
    .admin-table tbody tr { border-bottom: 1px solid var(--color-border); transition: var(--transition); }
    .admin-table tbody tr:hover { background: var(--color-surface); }
    .action-btn {
        width: 32px; height: 32px;
        display: inline-flex; align-items: center; justify-content: center;
        border-radius: var(--radius-sm);
        border: 1.5px solid var(--color-border);
        background: transparent;
        transition: var(--transition);
        font-size: .75rem;
        color: var(--color-text-muted);
    }
    .action-btn:hover { border-color: var(--color-primary); color: var(--color-primary); }
    .action-btn.danger:hover { border-color: var(--color-danger); color: var(--color-danger); }
    .action-btn.primary:hover { border-color: #3498db; color: #3498db; }
</style>
@endsection

@section('content')
<div class="container py-5 animate-in">

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <a href="{{ route('admin.dashboard') }}" style="font-size:.82rem;color:var(--color-text-muted);text-decoration:none;">
                <i class="fas fa-arrow-left me-1"></i>Dashboard
            </a>
            <h1 class="fw-700 ls-tight mt-1 mb-0" style="font-size:1.5rem;">
                <i class="fas fa-couch me-2" style="color:var(--color-accent);"></i>Gestión de Muebles
            </h1>
        </div>
        <a href="{{ route('admin.muebles.create') }}" class="btn btn-elegant-dark btn-sm">
            <i class="fas fa-plus me-2"></i>Nuevo producto
        </a>
    </div>

    {{-- Buscador --}}
    <form method="GET" class="mb-4">
        <div class="input-group" style="max-width:380px;">
            <input type="text" name="search" class="form-control" placeholder="Buscar producto…"
                   value="{{ request('search') }}"
                   style="border:1.5px solid var(--color-border);border-radius:var(--radius-sm) 0 0 var(--radius-sm);font-size:.88rem;">
            <button class="btn btn-elegant-dark" type="submit" style="border-radius:0 var(--radius-sm) var(--radius-sm) 0;"><i class="fas fa-search"></i></button>
            @if(request('search'))
                <a href="{{ route('admin.muebles.index') }}" class="btn btn-outline-elegant ms-1" style="border-radius:var(--radius-sm);"><i class="fas fa-times"></i></a>
            @endif
        </div>
    </form>

    @if(count($muebles) > 0)
        <div style="background:var(--color-card);border:1px solid var(--color-border);border-radius:var(--radius-lg);overflow:hidden;">
            <div class="table-responsive">
                <table class="table align-middle mb-0 admin-table">
                    <thead>
                        <tr>
                            <th style="width:50px;">ID</th>
                            <th>Nombre</th>
                            <th>Categorías</th>
                            <th class="text-end">Precio</th>
                            <th class="text-center">Stock</th>
                            <th class="text-center">Dest.</th>
                            <th class="text-end" style="width:140px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($muebles as $m)
                            <tr>
                                <td style="color:var(--color-text-muted);font-size:.78rem;">{{ $m['id'] }}</td>
                                <td>
                                    <div class="fw-600">{{ $m['nombre'] }}</div>
                                    @if(!empty($m['color_principal']))
                                        <div style="font-size:.72rem;color:var(--color-text-muted);">{{ $m['color_principal'] }}</div>
                                    @endif
                                </td>
                                <td>
                                    @foreach($m['categorias'] ?? [] as $cat)
                                        <span style="font-size:.65rem;font-weight:500;color:var(--color-text-muted);background:var(--color-surface);padding:.15rem .4rem;border-radius:4px;border:1px solid var(--color-border);">
                                            {{ $cat['nombre'] }}
                                        </span>
                                    @endforeach
                                </td>
                                <td class="text-end fw-600">{{ number_format($m['precio'], 2) }} €</td>
                                <td class="text-center">
                                    <span style="font-size:.7rem;font-weight:600;padding:.2rem .5rem;border-radius:4px;{{ ($m['stock'] ?? 0) > 0 ? 'background:rgba(45,138,78,.08);color:var(--color-success);' : 'background:rgba(192,57,43,.08);color:var(--color-danger);' }}">
                                        {{ $m['stock'] ?? 0 }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    @if($m['destacado'] ?? false)
                                        <i class="fas fa-star" style="color:var(--color-accent);font-size:.8rem;"></i>
                                    @else
                                        <i class="far fa-star" style="color:var(--color-border);font-size:.8rem;"></i>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="d-flex gap-1 justify-content-end">
                                        <a href="{{ route('muebles.show', $m['id']) }}" class="action-btn" title="Ver" target="_blank"><i class="fas fa-eye"></i></a>
                                        <a href="{{ route('admin.muebles.edit', $m['id']) }}" class="action-btn primary" title="Editar"><i class="fas fa-edit"></i></a>
                                        <form action="{{ route('admin.muebles.destroy', $m['id']) }}" method="POST"
                                              onsubmit="return confirm('¿Eliminar «{{ addslashes($m['nombre']) }}»?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="action-btn danger" title="Eliminar"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        @if(($paginacion['last_page'] ?? 1) > 1)
            <nav class="mt-4">
                <ul class="pagination justify-content-center">
                    @for($p = 1; $p <= $paginacion['last_page']; $p++)
                        <li class="page-item {{ $p == $paginacion['current_page'] ? 'active' : '' }}">
                            <a class="page-link" href="{{ request()->fullUrlWithQuery(['page' => $p]) }}"
                               style="border:1px solid var(--color-border);border-radius:var(--radius-sm)!important;margin:0 2px;font-size:.85rem;{{ $p == $paginacion['current_page'] ? 'background:var(--color-primary);border-color:var(--color-primary);color:#fff;' : 'color:var(--color-text);' }}">
                                {{ $p }}
                            </a>
                        </li>
                    @endfor
                </ul>
            </nav>
        @endif

        <p style="font-size:.78rem;color:var(--color-text-muted);margin-top:.5rem;">Total: {{ $paginacion['total'] ?? count($muebles) }} producto(s)</p>
    @else
        <div class="text-center py-5">
            <div style="width:80px;height:80px;border-radius:50%;background:var(--color-surface);display:inline-flex;align-items:center;justify-content:center;margin-bottom:1rem;">
                <i class="fas fa-box-open fa-2x" style="color:var(--color-border);"></i>
            </div>
            <h5 style="color:var(--color-text-muted);font-weight:600;">No hay productos</h5>
            <a href="{{ route('admin.muebles.create') }}" class="btn btn-elegant-dark mt-2"><i class="fas fa-plus me-1"></i>Crear producto</a>
        </div>
    @endif
</div>
@endsection
