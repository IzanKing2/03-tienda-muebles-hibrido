@extends('layouts.app')

@section('title', 'Catálogo de Muebles')

@section('content')

{{-- Hero --}}
<section class="hero text-white py-5 mb-4">
    <div class="container py-3 text-center">
        <h1 class="display-5 fw-bold mb-2"><i class="fas fa-couch me-2"></i>Catálogo de Muebles</h1>
        <p class="lead opacity-75">Encuentra el mueble perfecto para tu hogar</p>
    </div>
</section>

<div class="container pb-5">

    {{-- Filtros --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('muebles.index') }}" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Buscar</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" name="search" class="form-control" placeholder="Nombre o descripción…"
                               value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-semibold">Precio mín.</label>
                    <input type="number" name="precio_min" class="form-control" placeholder="0"
                           value="{{ request('precio_min') }}" min="0">
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-semibold">Precio máx.</label>
                    <input type="number" name="precio_max" class="form-control" placeholder="9999"
                           value="{{ request('precio_max') }}" min="0">
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-semibold">Ordenar por</label>
                    <select name="sort" class="form-select">
                        <option value="id"     {{ request('sort') === 'id'         ? 'selected' : '' }}>Por defecto</option>
                        <option value="precio" {{ request('sort') === 'precio'     ? 'selected' : '' }}>Precio</option>
                        <option value="nombre" {{ request('sort') === 'nombre'     ? 'selected' : '' }}>Nombre</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-semibold">Orden</label>
                    <select name="order" class="form-select">
                        <option value="asc"  {{ request('order', 'asc') === 'asc'  ? 'selected' : '' }}>Ascendente</option>
                        <option value="desc" {{ request('order') === 'desc'        ? 'selected' : '' }}>Descendente</option>
                    </select>
                </div>
                <div class="col-12 d-flex gap-2">
                    <button type="submit" class="btn btn-dark">
                        <i class="fas fa-filter me-1"></i>Filtrar
                    </button>
                    <a href="{{ route('muebles.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-1"></i>Limpiar
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Resultados --}}
    @if(isset($muebles) && count($muebles) > 0)
        <p class="text-muted small mb-3">
            Mostrando {{ count($muebles) }} producto(s)
            @if($paginacion['total'] > 0) de {{ $paginacion['total'] }} en total @endif
        </p>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
            @foreach($muebles as $mueble)
                <div class="col">
                    <div class="card h-100 shadow-sm card-producto border-0">
                        {{-- Imagen --}}
                        @php
                            $imagenUrl = null;
                            if (!empty($mueble['galeria']['imagenes'])) {
                                foreach ($mueble['galeria']['imagenes'] as $img) {
                                    if ($img['es_principal']) { $imagenUrl = $img['ruta']; break; }
                                }
                                if (!$imagenUrl) $imagenUrl = $mueble['galeria']['imagenes'][0]['ruta'] ?? null;
                            }
                            if (!$imagenUrl && !empty($mueble['imagen_principal'])) {
                                $imagenUrl = $mueble['imagen_principal'];
                            }
                        @endphp
                        @if($imagenUrl)
                            <img src="{{ asset('storage/' . $imagenUrl) }}" class="card-img-top"
                                 alt="{{ $mueble['nombre'] }}" style="height:200px;object-fit:cover;">
                        @else
                            <div class="d-flex align-items-center justify-content-center bg-light"
                                 style="height:200px;">
                                <i class="fas fa-couch fa-3x text-secondary opacity-50"></i>
                            </div>
                        @endif

                        <div class="card-body d-flex flex-column">
                            <h6 class="card-title fw-semibold mb-1">{{ $mueble['nombre'] }}</h6>

                            @if(!empty($mueble['categorias']))
                                <div class="mb-2">
                                    @foreach($mueble['categorias'] as $cat)
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary border me-1" style="font-size:.7rem;">
                                            {{ $cat['nombre'] }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif

                            <p class="card-text text-muted small flex-grow-1" style="overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;">
                                {{ $mueble['descripcion'] ?? '' }}
                            </p>

                            <div class="d-flex align-items-center justify-content-between mt-2">
                                <span class="fw-bold fs-5 text-dark">{{ number_format($mueble['precio'], 2) }} €</span>
                                @if(isset($mueble['stock']))
                                    <span class="badge {{ $mueble['stock'] > 0 ? 'bg-success' : 'bg-danger' }}">
                                        {{ $mueble['stock'] > 0 ? 'En stock' : 'Agotado' }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="card-footer bg-transparent border-0 pt-0 pb-3 px-3">
                            <a href="{{ route('muebles.show', $mueble['id']) }}"
                               class="btn btn-dark btn-sm w-100">
                                <i class="fas fa-eye me-1"></i>Ver detalle
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Paginación simple --}}
        @if($paginacion['last_page'] > 1)
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

    @else
        <div class="text-center py-5">
            <i class="fas fa-box-open fa-4x text-secondary opacity-50 mb-3"></i>
            <h5 class="text-muted">No se encontraron productos</h5>
            @if(request()->anyFilled(['search','precio_min','precio_max']))
                <a href="{{ route('muebles.index') }}" class="btn btn-outline-dark mt-3">Ver todos los productos</a>
            @endif
        </div>
    @endif

</div>
@endsection
