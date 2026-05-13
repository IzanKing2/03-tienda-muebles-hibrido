@extends('layouts.app')

@section('title', 'MueblesHíbrido — El mueble perfecto para cada espacio')

@section('head')
<style>
    .hero-home {
        background: linear-gradient(135deg, #1a1a1a 0%, #3d2b1f 50%, #5c4033 100%);
        min-height: 520px;
        position: relative;
        overflow: hidden;
    }
    .hero-home::before {
        content: '';
        position: absolute;
        inset: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }
    .categoria-card {
        transition: transform .2s, box-shadow .2s;
        cursor: pointer;
        text-decoration: none !important;
        color: inherit !important;
    }
    .categoria-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 30px rgba(0,0,0,.15) !important;
    }
    .categoria-card .cat-icon {
        width: 64px;
        height: 64px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        margin: 0 auto 12px;
        font-size: 1.6rem;
        background: linear-gradient(135deg, #f8f5f0, #e8dfd6);
        color: #5c4033;
        transition: background .2s;
    }
    .categoria-card:hover .cat-icon {
        background: linear-gradient(135deg, #5c4033, #3d2b1f);
        color: #fff;
    }
    .banner-cta {
        background: linear-gradient(135deg, #2c2c2c 0%, #5c4033 100%);
    }
    .feature-icon {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        margin: 0 auto 16px;
    }
</style>
@endsection

@section('content')

{{-- ═══ HERO ═══════════════════════════════════════════════════════════════ --}}
<section class="hero-home d-flex align-items-center text-white position-relative">
    <div class="container py-5 text-center position-relative" style="z-index:1;">
        <span class="badge bg-warning text-dark fw-semibold mb-3 px-3 py-2" style="font-size:.85rem;">
            <i class="fas fa-star me-1"></i>Más de 20 productos únicos
        </span>
        <h1 class="display-4 fw-bold mb-3" style="letter-spacing:-1px;">
            El mueble perfecto<br>
            <span style="color:#f0c070;">para cada espacio</span>
        </h1>
        <p class="lead opacity-75 mb-4 mx-auto" style="max-width:520px;">
            Diseño, calidad y confort en una sola tienda. Desde sofás hasta escritorios,
            encuentra el estilo que define tu hogar.
        </p>
        <div class="d-flex gap-3 justify-content-center flex-wrap">
            <a href="{{ route('muebles.index') }}" class="btn btn-warning btn-lg fw-semibold px-4">
                <i class="fas fa-couch me-2"></i>Ver catálogo completo
            </a>
            @guest
                <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg px-4">
                    <i class="fas fa-user-plus me-2"></i>Crear cuenta gratis
                </a>
            @endguest
        </div>
    </div>
</section>

{{-- ═══ CARACTERÍSTICAS ═════════════════════════════════════════════════════ --}}
<section class="py-4 bg-white border-bottom">
    <div class="container">
        <div class="row g-3 text-center">
            <div class="col-6 col-md-3">
                <div class="d-flex align-items-center gap-2 justify-content-center">
                    <i class="fas fa-shield-alt text-success fs-5"></i>
                    <span class="small fw-semibold text-muted">Calidad garantizada</span>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="d-flex align-items-center gap-2 justify-content-center">
                    <i class="fas fa-truck text-primary fs-5"></i>
                    <span class="small fw-semibold text-muted">Envío a toda España</span>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="d-flex align-items-center gap-2 justify-content-center">
                    <i class="fas fa-undo-alt text-warning fs-5"></i>
                    <span class="small fw-semibold text-muted">30 días de devolución</span>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="d-flex align-items-center gap-2 justify-content-center">
                    <i class="fas fa-headset text-danger fs-5"></i>
                    <span class="small fw-semibold text-muted">Atención 24/7</span>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ═══ CATEGORÍAS ══════════════════════════════════════════════════════════ --}}
@if(!empty($categorias))
@php
    $catIcons = [
        'Sillas'      => 'fa-chair',
        'Mesas'       => 'fa-border-none',
        'Sofás'       => 'fa-couch',
        'Camas'       => 'fa-bed',
        'Armarios'    => 'fa-door-closed',
        'Estanterías' => 'fa-book-open',
        'Sillones'    => 'fa-loveseat',
        'Escritorios' => 'fa-desktop',
    ];
@endphp
<section class="py-5">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h2 class="h4 fw-bold mb-0">Explora por categoría</h2>
                <p class="text-muted small mb-0">Encuentra exactamente lo que buscas</p>
            </div>
            <a href="{{ route('muebles.index') }}" class="btn btn-outline-dark btn-sm">
                Ver todo <i class="fas fa-arrow-right ms-1"></i>
            </a>
        </div>
        <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-{{ min(count($categorias), 8) > 4 ? '4' : count($categorias) }} g-3">
            @foreach($categorias as $cat)
                <div class="col">
                    <a href="{{ route('muebles.index', ['categoria_id' => $cat['id']]) }}"
                       class="card border-0 shadow-sm text-center p-3 categoria-card h-100">
                        <div class="cat-icon mx-auto">
                            <i class="fas {{ $catIcons[$cat['nombre']] ?? 'fa-cube' }}"></i>
                        </div>
                        <div class="fw-semibold small">{{ $cat['nombre'] }}</div>
                        @if(!empty($cat['descripcion']))
                            <div class="text-muted" style="font-size:.72rem;">{{ Str::limit($cat['descripcion'], 40) }}</div>
                        @endif
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ═══ PRODUCTOS DESTACADOS ════════════════════════════════════════════════ --}}
@if(!empty($destacados))
<section class="py-5" style="background:#f8f5f0;">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <span class="badge bg-warning text-dark mb-1"><i class="fas fa-star me-1"></i>Destacados</span>
                <h2 class="h4 fw-bold mb-0">Nuestras mejores piezas</h2>
                <p class="text-muted small mb-0">Selección especial de nuestro equipo</p>
            </div>
            <a href="{{ route('muebles.index') }}" class="btn btn-outline-dark btn-sm d-none d-md-inline-flex">
                Ver catálogo completo <i class="fas fa-arrow-right ms-1"></i>
            </a>
        </div>

        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
            @foreach($destacados as $m)
                <div class="col">
                    <div class="card h-100 shadow-sm card-producto border-0">
                        {{-- Badge destacado --}}
                        <div class="position-relative">
                            @php
                                $imgUrl = null;
                                foreach ($m['galeria']['imagenes'] ?? [] as $img) {
                                    if ($img['es_principal']) { $imgUrl = $img['ruta']; break; }
                                }
                                if (!$imgUrl) $imgUrl = ($m['galeria']['imagenes'] ?? [])[0]['ruta'] ?? null;
                                if (!$imgUrl) $imgUrl = $m['imagen_principal'] ?? null;
                            @endphp
                            @if($imgUrl)
                                <img src="{{ asset('storage/' . $imgUrl) }}" class="card-img-top"
                                     alt="{{ $m['nombre'] }}" style="height:200px;object-fit:cover;">
                            @else
                                <div class="d-flex align-items-center justify-content-center bg-light" style="height:200px;">
                                    <i class="fas fa-couch fa-3x text-secondary opacity-50"></i>
                                </div>
                            @endif
                            <span class="position-absolute top-0 end-0 m-2 badge bg-warning text-dark">
                                <i class="fas fa-star me-1"></i>Destacado
                            </span>
                        </div>

                        <div class="card-body d-flex flex-column">
                            <h6 class="card-title fw-semibold mb-1">{{ $m['nombre'] }}</h6>
                            @if(!empty($m['categorias']))
                                <div class="mb-2">
                                    @foreach($m['categorias'] as $cat)
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary border" style="font-size:.7rem;">
                                            {{ $cat['nombre'] }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                            <p class="card-text text-muted small flex-grow-1"
                               style="overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;">
                                {{ $m['descripcion'] ?? '' }}
                            </p>
                            <div class="d-flex align-items-center justify-content-between mt-2">
                                <span class="fw-bold fs-5">{{ number_format($m['precio'], 2) }} €</span>
                                <span class="badge {{ ($m['stock'] ?? 0) > 0 ? 'bg-success' : 'bg-danger' }}">
                                    {{ ($m['stock'] ?? 0) > 0 ? 'En stock' : 'Agotado' }}
                                </span>
                            </div>
                        </div>

                        <div class="card-footer bg-transparent border-0 pt-0 pb-3 px-3">
                            <a href="{{ route('muebles.show', $m['id']) }}" class="btn btn-dark btn-sm w-100">
                                <i class="fas fa-eye me-1"></i>Ver detalle
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="text-center mt-4 d-md-none">
            <a href="{{ route('muebles.index') }}" class="btn btn-outline-dark">
                Ver catálogo completo <i class="fas fa-arrow-right ms-1"></i>
            </a>
        </div>
    </div>
</section>
@endif

{{-- ═══ BANNER CTA ══════════════════════════════════════════════════════════ --}}
<section class="banner-cta text-white py-5">
    <div class="container text-center py-3">
        <h2 class="h3 fw-bold mb-2">¿No encuentras lo que buscas?</h2>
        <p class="opacity-75 mb-4">Tenemos más de 20 piezas únicas esperándote en el catálogo.</p>
        <div class="d-flex gap-3 justify-content-center flex-wrap">
            <a href="{{ route('muebles.index') }}" class="btn btn-warning btn-lg fw-semibold">
                <i class="fas fa-search me-2"></i>Explorar todo el catálogo
            </a>
            @guest
                <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg">
                    <i class="fas fa-user-plus me-2"></i>Registrarse gratis
                </a>
            @endguest
        </div>
    </div>
</section>

{{-- ═══ NOVEDADES ═══════════════════════════════════════════════════════════ --}}
@if(!empty($recientes))
<section class="py-5 bg-white">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <span class="badge bg-dark mb-1"><i class="fas fa-clock me-1"></i>Novedades</span>
                <h2 class="h4 fw-bold mb-0">Últimas incorporaciones</h2>
            </div>
        </div>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-4">
            @foreach($recientes as $m)
                <div class="col">
                    <div class="card h-100 shadow-sm card-producto border-0">
                        @php
                            $imgUrl = null;
                            foreach ($m['galeria']['imagenes'] ?? [] as $img) {
                                if ($img['es_principal']) { $imgUrl = $img['ruta']; break; }
                            }
                            if (!$imgUrl) $imgUrl = ($m['galeria']['imagenes'] ?? [])[0]['ruta'] ?? null;
                        @endphp
                        @if($imgUrl)
                            <img src="{{ asset('storage/' . $imgUrl) }}" class="card-img-top"
                                 alt="{{ $m['nombre'] }}" style="height:180px;object-fit:cover;">
                        @else
                            <div class="d-flex align-items-center justify-content-center bg-light" style="height:180px;">
                                <i class="fas fa-couch fa-2x text-secondary opacity-50"></i>
                            </div>
                        @endif
                        <div class="card-body">
                            <h6 class="fw-semibold mb-1">{{ $m['nombre'] }}</h6>
                            <div class="fw-bold text-dark">{{ number_format($m['precio'], 2) }} €</div>
                        </div>
                        <div class="card-footer bg-transparent border-0 pt-0 pb-3 px-3">
                            <a href="{{ route('muebles.show', $m['id']) }}" class="btn btn-outline-dark btn-sm w-100">
                                Ver detalle
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ═══ VENTAJAS ════════════════════════════════════════════════════════════ --}}
<section class="py-5" style="background:#f8f5f0;">
    <div class="container">
        <h2 class="h4 fw-bold text-center mb-5">¿Por qué elegirnos?</h2>
        <div class="row g-4 text-center">
            <div class="col-sm-6 col-md-3">
                <div class="feature-icon bg-warning bg-opacity-10 text-warning mx-auto">
                    <i class="fas fa-medal"></i>
                </div>
                <h6 class="fw-bold">Calidad premium</h6>
                <p class="text-muted small mb-0">Materiales seleccionados y acabados de primera calidad en cada pieza.</p>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="feature-icon bg-primary bg-opacity-10 text-primary mx-auto">
                    <i class="fas fa-paint-brush"></i>
                </div>
                <h6 class="fw-bold">Diseño exclusivo</h6>
                <p class="text-muted small mb-0">Estilos contemporáneos, nórdicos, industriales y clásicos bajo un mismo techo.</p>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="feature-icon bg-success bg-opacity-10 text-success mx-auto">
                    <i class="fas fa-leaf"></i>
                </div>
                <h6 class="fw-bold">Sostenibilidad</h6>
                <p class="text-muted small mb-0">Madera certificada y procesos responsables con el medio ambiente.</p>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="feature-icon bg-danger bg-opacity-10 text-danger mx-auto">
                    <i class="fas fa-tools"></i>
                </div>
                <h6 class="fw-bold">Montaje incluido</h6>
                <p class="text-muted small mb-0">Servicio de montaje profesional disponible en tu ciudad.</p>
            </div>
        </div>
    </div>
</section>

@endsection
