@extends('layouts.app')

@section('title', 'Catálogo de Muebles')

@section('head')
<style>
    .filtro-activo { background:#ffc107;color:#000;font-weight:600; }
    .page-link { border-radius: 6px !important; margin: 0 2px; min-width: 38px; text-align:center; }
    .page-item.active .page-link { background:#2c2c2c; border-color:#2c2c2c; }
    .page-link:hover:not(.active) { background:#f0ebe4; color:#2c2c2c; }
</style>
@endsection

@section('content')

{{-- Hero mini --}}
<section class="hero text-white py-4 mb-0">
    <div class="container py-2 text-center">
        <h1 class="display-6 fw-bold mb-1"><i class="fas fa-couch me-2"></i>Catálogo de Muebles</h1>
        <p class="opacity-75 mb-0">Encuentra el mueble perfecto para tu hogar</p>
    </div>
</section>

<div class="container py-4 pb-5">
    <div class="row g-4">

        {{-- ══ SIDEBAR FILTROS ══ --}}
        <div class="col-lg-3">
            <div class="card shadow-sm border-0 sticky-top" style="top:76px;">
                <div class="card-header bg-dark text-white fw-semibold">
                    <i class="fas fa-filter me-2"></i>Filtros
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('muebles.index') }}" id="formFiltros">

                        {{-- Búsqueda --}}
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-muted">Buscar</label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                                <input type="text" name="search" class="form-control"
                                       placeholder="Nombre o descripción…"
                                       value="{{ request('search') }}">
                            </div>
                        </div>

                        {{-- Categoría --}}
                        @if(!empty($categorias))
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-muted">Categoría</label>
                            <select name="categoria_id" class="form-select form-select-sm">
                                <option value="">Todas las categorías</option>
                                @foreach($categorias as $cat)
                                    <option value="{{ $cat['id'] }}"
                                        {{ request('categoria_id') == $cat['id'] ? 'selected' : '' }}>
                                        {{ $cat['nombre'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        {{-- Precio --}}
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-muted">Rango de precio</label>
                            <div class="d-flex gap-2 align-items-center">
                                <input type="number" name="precio_min" class="form-control form-control-sm"
                                       placeholder="Mín." value="{{ request('precio_min') }}" min="0">
                                <span class="text-muted small">—</span>
                                <input type="number" name="precio_max" class="form-control form-control-sm"
                                       placeholder="Máx." value="{{ request('precio_max') }}" min="0">
                            </div>
                        </div>

                        {{-- Ordenar --}}
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-muted">Ordenar por</label>
                            <select name="sort" class="form-select form-select-sm">
                                <option value="id"         {{ request('sort','id') === 'id'         ? 'selected':'' }}>Por defecto</option>
                                <option value="precio"     {{ request('sort') === 'precio'          ? 'selected':'' }}>Precio</option>
                                <option value="nombre"     {{ request('sort') === 'nombre'          ? 'selected':'' }}>Nombre A-Z</option>
                                <option value="created_at" {{ request('sort') === 'created_at'      ? 'selected':'' }}>Más recientes</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-semibold text-muted">Orden</label>
                            <select name="order" class="form-select form-select-sm">
                                <option value="asc"  {{ request('order','asc') === 'asc'  ? 'selected':'' }}>Ascendente</option>
                                <option value="desc" {{ request('order') === 'desc'       ? 'selected':'' }}>Descendente</option>
                            </select>
                        </div>

                        {{-- Mantener per_page --}}
                        <input type="hidden" name="per_page" value="{{ request('per_page', 12) }}">

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-dark btn-sm">
                                <i class="fas fa-filter me-1"></i>Aplicar filtros
                            </button>
                            <a href="{{ route('muebles.index') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-times me-1"></i>Limpiar todo
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- ══ RESULTADOS ══ --}}
        <div class="col-lg-9">

            {{-- Barra superior: resultados + pills activos + per_page --}}
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                <div>
                    @if($paginacion['total'] > 0)
                        <span class="text-muted small">
                            Mostrando
                            <strong>{{ $paginacion['from'] }}</strong>–<strong>{{ $paginacion['to'] }}</strong>
                            de <strong>{{ $paginacion['total'] }}</strong> productos
                        </span>
                    @else
                        <span class="text-muted small">Sin resultados</span>
                    @endif

                    {{-- Pills de filtros activos --}}
                    @if(request()->anyFilled(['search','categoria_id','precio_min','precio_max']))
                        <div class="d-inline-flex flex-wrap gap-1 ms-2">
                            @if(request('search'))
                                <a href="{{ request()->fullUrlWithQuery(['search'=>null,'page'=>1]) }}"
                                   class="badge filtro-activo text-decoration-none">
                                    «{{ request('search') }}» <i class="fas fa-times ms-1"></i>
                                </a>
                            @endif
                            @if(request('categoria_id'))
                                @php
                                    $catActiva = collect($categorias)->firstWhere('id', request('categoria_id'));
                                @endphp
                                <a href="{{ request()->fullUrlWithQuery(['categoria_id'=>null,'page'=>1]) }}"
                                   class="badge filtro-activo text-decoration-none">
                                    {{ $catActiva['nombre'] ?? 'Categoría' }} <i class="fas fa-times ms-1"></i>
                                </a>
                            @endif
                            @if(request('precio_min') || request('precio_max'))
                                <a href="{{ request()->fullUrlWithQuery(['precio_min'=>null,'precio_max'=>null,'page'=>1]) }}"
                                   class="badge filtro-activo text-decoration-none">
                                    {{ request('precio_min','0') }}€ – {{ request('precio_max','∞') }}€
                                    <i class="fas fa-times ms-1"></i>
                                </a>
                            @endif
                        </div>
                    @endif
                </div>

                {{-- Resultados por página --}}
                <div class="d-flex align-items-center gap-2">
                    <span class="small text-muted d-none d-sm-inline">Por página:</span>
                    @foreach([12, 24, 48] as $pp)
                        <a href="{{ request()->fullUrlWithQuery(['per_page' => $pp, 'page' => 1]) }}"
                           class="btn btn-sm {{ request('per_page', 12) == $pp ? 'btn-dark' : 'btn-outline-secondary' }}">
                            {{ $pp }}
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- Grid de productos --}}
            @if(count($muebles) > 0)
                <div class="row row-cols-1 row-cols-sm-2 row-cols-xl-3 g-4">
                    @foreach($muebles as $mueble)
                        <div class="col">
                            <div class="card h-100 shadow-sm card-producto border-0">
                                {{-- Imagen --}}
                                @php
                                    $imgUrl = null;
                                    foreach ($mueble['galeria']['imagenes'] ?? [] as $img) {
                                        if ($img['es_principal']) { $imgUrl = $img['ruta']; break; }
                                    }
                                    if (!$imgUrl) $imgUrl = ($mueble['galeria']['imagenes'] ?? [])[0]['ruta'] ?? null;
                                    if (!$imgUrl) $imgUrl = $mueble['imagen_principal'] ?? null;
                                @endphp
                                <div class="position-relative">
                                    @if($imgUrl)
                                        <img src="{{ asset('storage/' . $imgUrl) }}" class="card-img-top"
                                             alt="{{ $mueble['nombre'] }}" style="height:200px;object-fit:cover;">
                                    @else
                                        <div class="d-flex align-items-center justify-content-center bg-light"
                                             style="height:200px;">
                                            <i class="fas fa-couch fa-3x text-secondary opacity-50"></i>
                                        </div>
                                    @endif
                                    @if($mueble['destacado'] ?? false)
                                        <span class="position-absolute top-0 end-0 m-2 badge bg-warning text-dark" style="font-size:.7rem;">
                                            <i class="fas fa-star me-1"></i>Destacado
                                        </span>
                                    @endif
                                </div>

                                <div class="card-body d-flex flex-column">
                                    <h6 class="card-title fw-semibold mb-1">{{ $mueble['nombre'] }}</h6>
                                    @if(!empty($mueble['categorias']))
                                        <div class="mb-2">
                                            @foreach($mueble['categorias'] as $cat)
                                                <a href="{{ request()->fullUrlWithQuery(['categoria_id' => $cat['id'], 'page' => 1]) }}"
                                                   class="badge bg-secondary bg-opacity-10 text-secondary border text-decoration-none me-1"
                                                   style="font-size:.7rem;">
                                                    {{ $cat['nombre'] }}
                                                </a>
                                            @endforeach
                                        </div>
                                    @endif
                                    <p class="card-text text-muted small" style="flex-grow:1;overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;line-clamp:2;-webkit-box-orient:vertical;">
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

                {{-- ══ PAGINACIÓN ══ --}}
                @if($paginacion['last_page'] > 1)
                @php
                    $current  = $paginacion['current_page'];
                    $last     = $paginacion['last_page'];
                    $window   = 2; // páginas a cada lado del actual

                    $pages = collect();
                    for ($p = 1; $p <= $last; $p++) {
                        if ($p === 1 || $p === $last
                            || ($p >= $current - $window && $p <= $current + $window)) {
                            $pages->push($p);
                        }
                    }
                    // Insertar null como separador (ellipsis)
                    $withEllipsis = collect();
                    $prev = null;
                    foreach ($pages as $p) {
                        if ($prev !== null && $p - $prev > 1) {
                            $withEllipsis->push(null);
                        }
                        $withEllipsis->push($p);
                        $prev = $p;
                    }
                @endphp
                <nav class="mt-4" aria-label="Paginación">
                    <ul class="pagination justify-content-center flex-wrap mb-0">

                        {{-- Anterior --}}
                        <li class="page-item {{ $current <= 1 ? 'disabled' : '' }}">
                            <a class="page-link"
                               href="{{ $current > 1 ? request()->fullUrlWithQuery(['page' => $current - 1]) : '#' }}"
                               aria-label="Anterior">
                                <i class="fas fa-chevron-left" style="font-size:.75rem;"></i>
                            </a>
                        </li>

                        @foreach($withEllipsis as $p)
                            @if($p === null)
                                <li class="page-item disabled">
                                    <span class="page-link border-0 bg-transparent">…</span>
                                </li>
                            @else
                                <li class="page-item {{ $p === $current ? 'active' : '' }}">
                                    <a class="page-link"
                                       href="{{ request()->fullUrlWithQuery(['page' => $p]) }}">
                                        {{ $p }}
                                    </a>
                                </li>
                            @endif
                        @endforeach

                        {{-- Siguiente --}}
                        <li class="page-item {{ $current >= $last ? 'disabled' : '' }}">
                            <a class="page-link"
                               href="{{ $current < $last ? request()->fullUrlWithQuery(['page' => $current + 1]) : '#' }}"
                               aria-label="Siguiente">
                                <i class="fas fa-chevron-right" style="font-size:.75rem;"></i>
                            </a>
                        </li>
                    </ul>

                    {{-- Info página actual --}}
                    <p class="text-muted small text-center mt-2 mb-0">
                        Página {{ $current }} de {{ $last }}
                    </p>
                </nav>
                @endif

            @else
                <div class="text-center py-5">
                    <i class="fas fa-box-open fa-4x text-secondary opacity-50 mb-3"></i>
                    <h5 class="text-muted">No se encontraron productos</h5>
                    @if(request()->anyFilled(['search','categoria_id','precio_min','precio_max']))
                        <p class="text-muted small">Prueba con otros filtros o</p>
                        <a href="{{ route('muebles.index') }}" class="btn btn-outline-dark mt-1">
                            Ver todos los productos
                        </a>
                    @endif
                </div>
            @endif

        </div>{{-- /col resultados --}}
    </div>{{-- /row --}}
</div>
@endsection
