@extends('layouts.app')

@section('title', 'Catálogo de Muebles')

@section('head')
<style>
    /* ── Hero mini del catálogo ── */
    .catalog-hero {
        background: linear-gradient(160deg, #1a1a2e 0%, #2a1f3d 100%);
        padding: 2.5rem 0;
        position: relative;
    }
    .catalog-hero::before {
        content: '';
        position: absolute; inset: 0;
        background: radial-gradient(circle at 80% 50%, rgba(201,169,110,.08) 0%, transparent 50%);
    }

    /* ── Sidebar de filtros ── */
    .filter-card {
        background: var(--color-card);
        border: 1px solid var(--color-border);
        border-radius: var(--radius-lg);
        overflow: hidden;
    }
    .filter-header {
        background: var(--color-primary);
        color: #fff;
        padding: .85rem 1.2rem;
        font-weight: 600;
        font-size: .88rem;
        display: flex; align-items: center; gap: .5rem;
    }
    .filter-body { padding: 1.2rem; }
    .filter-body .form-control,
    .filter-body .form-select {
        border: 1.5px solid var(--color-border);
        border-radius: var(--radius-sm);
        font-size: .85rem;
        transition: var(--transition);
    }
    .filter-body .form-control:focus,
    .filter-body .form-select:focus {
        border-color: var(--color-accent);
        box-shadow: 0 0 0 3px rgba(201,169,110,.1);
    }
    .filter-body .input-group-text {
        background: var(--color-surface);
        border: 1.5px solid var(--color-border);
        border-right: none;
        font-size: .8rem;
        color: var(--color-text-muted);
    }

    /* ── Tarjetas de producto ── */
    .product-card {
        background: var(--color-card);
        border: 1px solid var(--color-border);
        border-radius: var(--radius-lg);
        overflow: hidden;
        transition: var(--transition);
    }
    .product-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-hover);
    }
    .product-card .product-img {
        height: 210px; object-fit: cover; width: 100%;
        transition: transform .4s ease;
    }
    .product-card:hover .product-img { transform: scale(1.03); }
    .product-img-wrapper { overflow: hidden; position: relative; }

    /* ── Filtros activos (pills) ── */
    .filter-pill {
        display: inline-flex; align-items: center; gap: .3rem;
        background: rgba(201,169,110,.1);
        color: var(--color-accent);
        border: 1px solid rgba(201,169,110,.2);
        padding: .2rem .6rem;
        border-radius: 50px;
        font-size: .72rem;
        font-weight: 600;
        text-decoration: none;
        transition: var(--transition);
    }
    .filter-pill:hover {
        background: rgba(201,169,110,.2);
        color: var(--color-accent-hover);
    }

    /* ── Paginación elegante ── */
    .pagination .page-link {
        border: 1px solid var(--color-border);
        border-radius: var(--radius-sm) !important;
        margin: 0 2px;
        min-width: 38px;
        text-align: center;
        color: var(--color-text);
        font-weight: 500;
        font-size: .85rem;
        transition: var(--transition);
    }
    .pagination .page-item.active .page-link {
        background: var(--color-primary);
        border-color: var(--color-primary);
        color: #fff;
    }
    .pagination .page-link:hover:not(.active) {
        background: var(--color-surface);
        color: var(--color-primary);
    }

    /* ── Per page selector ── */
    .pp-btn {
        padding: .25rem .55rem;
        font-size: .75rem;
        font-weight: 600;
        border-radius: 6px;
        border: 1.5px solid var(--color-border);
        color: var(--color-text-muted);
        background: transparent;
        text-decoration: none;
        transition: var(--transition);
    }
    .pp-btn:hover { border-color: var(--color-primary); color: var(--color-primary); }
    .pp-btn.active {
        background: var(--color-primary);
        border-color: var(--color-primary);
        color: #fff;
    }
</style>
@endsection

@section('content')

{{-- Hero mini --}}
<section class="catalog-hero text-white">
    <div class="container text-center position-relative" style="z-index:1;">
        <div class="section-label justify-content-center" style="color:var(--color-accent);"><i class="fas fa-couch"></i>Colección completa</div>
        <h1 class="fw-700 ls-tight mb-1" style="font-size:1.8rem;">Catálogo de Muebles</h1>
        <p class="mb-0" style="opacity:.5;font-size:.9rem;font-weight:300;">Encuentra el mueble perfecto para tu hogar</p>
    </div>
</section>

<div class="container py-4 pb-5">
    <div class="row g-4">

        {{-- ══ SIDEBAR FILTROS ══ --}}
        <div class="col-lg-3">
            <div class="filter-card sticky-top" style="top:76px;">
                <div class="filter-header">
                    <i class="fas fa-sliders-h"></i>Filtros
                </div>
                <div class="filter-body">
                    <form method="GET" action="{{ route('muebles.index') }}" id="formFiltros">

                        {{-- Búsqueda --}}
                        <div class="mb-3">
                            <label class="form-label fw-600" style="font-size:.78rem;color:var(--color-text-muted);text-transform:uppercase;letter-spacing:.5px;">Buscar</label>
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
                            <label class="form-label fw-600" style="font-size:.78rem;color:var(--color-text-muted);text-transform:uppercase;letter-spacing:.5px;">Categoría</label>
                            <select name="categoria_id" class="form-select form-select-sm">
                                <option value="">Todas</option>
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
                            <label class="form-label fw-600" style="font-size:.78rem;color:var(--color-text-muted);text-transform:uppercase;letter-spacing:.5px;">Rango de precio</label>
                            <div class="d-flex gap-2 align-items-center">
                                <input type="number" name="precio_min" class="form-control form-control-sm"
                                       placeholder="Mín." value="{{ request('precio_min') }}" min="0">
                                <span style="color:var(--color-text-muted);font-size:.75rem;">—</span>
                                <input type="number" name="precio_max" class="form-control form-control-sm"
                                       placeholder="Máx." value="{{ request('precio_max') }}" min="0">
                            </div>
                        </div>

                        {{-- Ordenar --}}
                        <div class="mb-3">
                            <label class="form-label fw-600" style="font-size:.78rem;color:var(--color-text-muted);text-transform:uppercase;letter-spacing:.5px;">Ordenar por</label>
                            <select name="sort" class="form-select form-select-sm">
                                <option value="id"         {{ request('sort','id') === 'id'         ? 'selected':'' }}>Por defecto</option>
                                <option value="precio"     {{ request('sort') === 'precio'          ? 'selected':'' }}>Precio</option>
                                <option value="nombre"     {{ request('sort') === 'nombre'          ? 'selected':'' }}>Nombre A-Z</option>
                                <option value="created_at" {{ request('sort') === 'created_at'      ? 'selected':'' }}>Más recientes</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-600" style="font-size:.78rem;color:var(--color-text-muted);text-transform:uppercase;letter-spacing:.5px;">Orden</label>
                            <select name="order" class="form-select form-select-sm">
                                <option value="asc"  {{ request('order','asc') === 'asc'  ? 'selected':'' }}>Ascendente</option>
                                <option value="desc" {{ request('order') === 'desc'       ? 'selected':'' }}>Descendente</option>
                            </select>
                        </div>

                        <input type="hidden" name="per_page" value="{{ request('per_page', 12) }}">

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-elegant-dark btn-sm">
                                <i class="fas fa-filter me-1"></i>Aplicar filtros
                            </button>
                            <a href="{{ route('muebles.index') }}" class="btn btn-outline-elegant btn-sm">
                                <i class="fas fa-times me-1"></i>Limpiar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- ══ RESULTADOS ══ --}}
        <div class="col-lg-9">

            {{-- Barra superior --}}
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                <div>
                    @if($paginacion['total'] > 0)
                        <span class="text-muted" style="font-size:.82rem;">
                            Mostrando <strong>{{ $paginacion['from'] }}</strong>–<strong>{{ $paginacion['to'] }}</strong>
                            de <strong>{{ $paginacion['total'] }}</strong> productos
                        </span>
                    @else
                        <span class="text-muted" style="font-size:.82rem;">Sin resultados</span>
                    @endif

                    @if(request()->anyFilled(['search','categoria_id','precio_min','precio_max']))
                        <div class="d-inline-flex flex-wrap gap-1 ms-2">
                            @if(request('search'))
                                <a href="{{ request()->fullUrlWithQuery(['search'=>null,'page'=>1]) }}" class="filter-pill">
                                    «{{ request('search') }}» <i class="fas fa-times" style="font-size:.6rem;"></i>
                                </a>
                            @endif
                            @if(request('categoria_id'))
                                @php $catActiva = collect($categorias)->firstWhere('id', request('categoria_id')); @endphp
                                <a href="{{ request()->fullUrlWithQuery(['categoria_id'=>null,'page'=>1]) }}" class="filter-pill">
                                    {{ $catActiva['nombre'] ?? 'Categoría' }} <i class="fas fa-times" style="font-size:.6rem;"></i>
                                </a>
                            @endif
                            @if(request('precio_min') || request('precio_max'))
                                <a href="{{ request()->fullUrlWithQuery(['precio_min'=>null,'precio_max'=>null,'page'=>1]) }}" class="filter-pill">
                                    {{ request('precio_min','0') }}€ – {{ request('precio_max','∞') }}€ <i class="fas fa-times" style="font-size:.6rem;"></i>
                                </a>
                            @endif
                        </div>
                    @endif
                </div>

                <div class="d-flex align-items-center gap-2">
                    <span class="d-none d-sm-inline" style="font-size:.75rem;color:var(--color-text-muted);">Por página:</span>
                    @foreach([12, 24, 48] as $pp)
                        <a href="{{ request()->fullUrlWithQuery(['per_page' => $pp, 'page' => 1]) }}"
                           class="pp-btn {{ request('per_page', 12) == $pp ? 'active' : '' }}">
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
                            <div class="product-card h-100 d-flex flex-column">
                                @php
                                    $imgUrl = null;
                                    foreach ($mueble['galeria']['imagenes'] ?? [] as $img) {
                                        if ($img['es_principal']) { $imgUrl = $img['ruta']; break; }
                                    }
                                    if (!$imgUrl) $imgUrl = ($mueble['galeria']['imagenes'] ?? [])[0]['ruta'] ?? null;
                                    if (!$imgUrl) $imgUrl = $mueble['imagen_principal'] ?? null;
                                @endphp
                                <div class="product-img-wrapper">
                                    @if($imgUrl)
                                        <img src="{{ asset('storage/' . $imgUrl) }}" class="product-img" alt="{{ $mueble['nombre'] }}">
                                    @else
                                        <div class="d-flex align-items-center justify-content-center" style="height:210px;background:var(--color-surface);">
                                            <i class="fas fa-couch fa-3x" style="color:var(--color-border);"></i>
                                        </div>
                                    @endif
                                    @if($mueble['destacado'] ?? false)
                                        <span class="position-absolute top-0 end-0 m-3" style="background:rgba(201,169,110,.9);color:var(--color-primary);font-size:.65rem;font-weight:700;padding:.25rem .55rem;border-radius:5px;">
                                            <i class="fas fa-star me-1"></i>Destacado
                                        </span>
                                    @endif
                                </div>

                                <div class="p-3 d-flex flex-column flex-grow-1">
                                    <h6 class="fw-600 mb-1" style="font-size:.9rem;">{{ $mueble['nombre'] }}</h6>
                                    @if(!empty($mueble['categorias']))
                                        <div class="mb-2">
                                            @foreach($mueble['categorias'] as $cat)
                                                <a href="{{ request()->fullUrlWithQuery(['categoria_id' => $cat['id'], 'page' => 1]) }}"
                                                   style="font-size:.62rem;font-weight:500;color:var(--color-text-muted);background:var(--color-surface);padding:.15rem .4rem;border-radius:4px;text-decoration:none;">
                                                    {{ $cat['nombre'] }}
                                                </a>
                                            @endforeach
                                        </div>
                                    @endif
                                    <p class="text-muted mb-2 flex-grow-1" style="font-size:.8rem;overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;line-clamp:2;-webkit-box-orient:vertical;">
                                        {{ $mueble['descripcion'] ?? '' }}
                                    </p>
                                    <div class="d-flex align-items-center justify-content-between mt-auto pt-2" style="border-top:1px solid var(--color-border);">
                                        <span class="fw-700" style="font-size:1.05rem;color:var(--color-primary);">{{ number_format($mueble['precio'], 2) }} €</span>
                                        @if(isset($mueble['stock']))
                                            <span style="font-size:.68rem;font-weight:600;padding:.15rem .45rem;border-radius:4px;{{ $mueble['stock'] > 0 ? 'background:rgba(45,138,78,.08);color:var(--color-success);' : 'background:rgba(192,57,43,.08);color:var(--color-danger);' }}">
                                                {{ $mueble['stock'] > 0 ? 'En stock' : 'Agotado' }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="px-3 pb-3">
                                    <a href="{{ route('muebles.show', $mueble['id']) }}" class="btn btn-elegant-dark btn-sm w-100">
                                        Ver detalle
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
                    $window   = 2;
                    $pages = collect();
                    for ($p = 1; $p <= $last; $p++) {
                        if ($p === 1 || $p === $last || ($p >= $current - $window && $p <= $current + $window)) {
                            $pages->push($p);
                        }
                    }
                    $withEllipsis = collect();
                    $prev = null;
                    foreach ($pages as $p) {
                        if ($prev !== null && $p - $prev > 1) { $withEllipsis->push(null); }
                        $withEllipsis->push($p);
                        $prev = $p;
                    }
                @endphp
                <nav class="mt-5" aria-label="Paginación">
                    <ul class="pagination justify-content-center flex-wrap mb-0">
                        <li class="page-item {{ $current <= 1 ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $current > 1 ? request()->fullUrlWithQuery(['page' => $current - 1]) : '#' }}">
                                <i class="fas fa-chevron-left" style="font-size:.7rem;"></i>
                            </a>
                        </li>
                        @foreach($withEllipsis as $p)
                            @if($p === null)
                                <li class="page-item disabled"><span class="page-link border-0 bg-transparent">…</span></li>
                            @else
                                <li class="page-item {{ $p === $current ? 'active' : '' }}">
                                    <a class="page-link" href="{{ request()->fullUrlWithQuery(['page' => $p]) }}">{{ $p }}</a>
                                </li>
                            @endif
                        @endforeach
                        <li class="page-item {{ $current >= $last ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $current < $last ? request()->fullUrlWithQuery(['page' => $current + 1]) : '#' }}">
                                <i class="fas fa-chevron-right" style="font-size:.7rem;"></i>
                            </a>
                        </li>
                    </ul>
                    <p class="text-muted text-center mt-2 mb-0" style="font-size:.78rem;">Página {{ $current }} de {{ $last }}</p>
                </nav>
                @endif

            @else
                <div class="text-center py-5">
                    <div style="width:80px;height:80px;border-radius:50%;background:var(--color-surface);display:inline-flex;align-items:center;justify-content:center;margin-bottom:1rem;">
                        <i class="fas fa-box-open fa-2x" style="color:var(--color-border);"></i>
                    </div>
                    <h5 style="color:var(--color-text-muted);font-weight:600;">No se encontraron productos</h5>
                    @if(request()->anyFilled(['search','categoria_id','precio_min','precio_max']))
                        <p class="text-muted" style="font-size:.85rem;">Prueba con otros filtros</p>
                        <a href="{{ route('muebles.index') }}" class="btn btn-outline-elegant mt-1">Ver todos</a>
                    @endif
                </div>
            @endif

        </div>
    </div>
</div>
@endsection
