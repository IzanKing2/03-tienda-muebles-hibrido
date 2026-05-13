@extends('layouts.app')

@section('title', 'Gestión de Muebles')

@section('content')
<div class="container py-5">

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <a href="{{ route('admin.dashboard') }}" class="text-muted text-decoration-none small">
                <i class="fas fa-arrow-left me-1"></i>Dashboard
            </a>
            <h1 class="h3 fw-bold mb-0 mt-1"><i class="fas fa-couch me-2"></i>Gestión de Muebles</h1>
        </div>
        <a href="{{ route('admin.muebles.create') }}" class="btn btn-dark">
            <i class="fas fa-plus me-2"></i>Nuevo producto
        </a>
    </div>

    {{-- Buscador --}}
    <form method="GET" class="mb-4">
        <div class="input-group" style="max-width:400px;">
            <input type="text" name="search" class="form-control" placeholder="Buscar producto…"
                   value="{{ request('search') }}">
            <button class="btn btn-dark" type="submit"><i class="fas fa-search"></i></button>
            @if(request('search'))
                <a href="{{ route('admin.muebles.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-times"></i>
                </a>
            @endif
        </div>
    </form>

    @if(count($muebles) > 0)
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th style="width:50px;">ID</th>
                                <th>Nombre</th>
                                <th>Categorías</th>
                                <th class="text-end">Precio</th>
                                <th class="text-center">Stock</th>
                                <th class="text-center">Destacado</th>
                                <th class="text-end" style="width:160px;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($muebles as $m)
                                <tr>
                                    <td class="text-muted small">{{ $m['id'] }}</td>
                                    <td>
                                        <div class="fw-semibold">{{ $m['nombre'] }}</div>
                                        @if(!empty($m['color_principal']))
                                            <div class="small text-muted">{{ $m['color_principal'] }}</div>
                                        @endif
                                    </td>
                                    <td>
                                        @foreach($m['categorias'] ?? [] as $cat)
                                            <span class="badge bg-secondary bg-opacity-10 text-dark border me-1 small">
                                                {{ $cat['nombre'] }}
                                            </span>
                                        @endforeach
                                    </td>
                                    <td class="text-end fw-semibold">{{ number_format($m['precio'], 2) }} €</td>
                                    <td class="text-center">
                                        <span class="badge {{ ($m['stock'] ?? 0) > 0 ? 'bg-success' : 'bg-danger' }}">
                                            {{ $m['stock'] ?? 0 }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @if($m['destacado'] ?? false)
                                            <i class="fas fa-star text-warning"></i>
                                        @else
                                            <i class="far fa-star text-muted"></i>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex gap-1 justify-content-end">
                                            <a href="{{ route('muebles.show', $m['id']) }}"
                                               class="btn btn-sm btn-outline-secondary" title="Ver en tienda" target="_blank">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.muebles.edit', $m['id']) }}"
                                               class="btn btn-sm btn-outline-primary" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.muebles.destroy', $m['id']) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('¿Eliminar «{{ addslashes($m['nombre']) }}»?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Paginación --}}
        @if(($paginacion['last_page'] ?? 1) > 1)
            <nav class="mt-4">
                <ul class="pagination justify-content-center">
                    @for($p = 1; $p <= $paginacion['last_page']; $p++)
                        <li class="page-item {{ $p == $paginacion['current_page'] ? 'active' : '' }}">
                            <a class="page-link" href="{{ request()->fullUrlWithQuery(['page' => $p]) }}">{{ $p }}</a>
                        </li>
                    @endfor
                </ul>
            </nav>
        @endif

        <p class="text-muted small mt-2">
            Total: {{ $paginacion['total'] ?? count($muebles) }} producto(s)
        </p>
    @else
        <div class="text-center py-5">
            <i class="fas fa-box-open fa-4x text-secondary opacity-50 mb-3"></i>
            <h5 class="text-muted">No hay productos</h5>
            <a href="{{ route('admin.muebles.create') }}" class="btn btn-dark mt-2">
                <i class="fas fa-plus me-1"></i>Crear primer producto
            </a>
        </div>
    @endif

</div>
@endsection
