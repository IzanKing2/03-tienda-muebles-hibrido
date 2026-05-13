@extends('layouts.app')

@section('title', 'MueblesHíbrido — El mueble perfecto para cada espacio')

@section('head')
<style>
    /* ── HERO: gradiente oscuro premium con patrón sutil ── */
    .hero-home {
        background: linear-gradient(160deg, #0f0f1a 0%, #1a1a2e 40%, #2a1f3d 100%);
        min-height: 560px;
        position: relative;
        overflow: hidden;
    }
    .hero-home::before {
        content: '';
        position: absolute; inset: 0;
        background: radial-gradient(circle at 70% 30%, rgba(201,169,110,.08) 0%, transparent 60%);
    }
    .hero-home::after {
        content: '';
        position: absolute; inset: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none'%3E%3Cg fill='%23ffffff' fill-opacity='0.02'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }
    .hero-badge {
        display: inline-flex; align-items: center; gap: .4rem;
        background: rgba(201,169,110,.12);
        border: 1px solid rgba(201,169,110,.25);
        color: var(--color-accent);
        padding: .4rem 1rem;
        border-radius: 50px;
        font-size: .8rem;
        font-weight: 600;
        letter-spacing: .3px;
    }
    .hero-title {
        font-size: clamp(2.2rem, 5vw, 3.5rem);
        font-weight: 800;
        letter-spacing: -1.5px;
        line-height: 1.1;
    }
    .hero-title span { color: var(--color-accent); }

    /* ── Categorías ── */
    .categoria-card {
        background: var(--color-card);
        border: 1px solid var(--color-border);
        border-radius: var(--radius-lg);
        padding: 1.5rem 1rem;
        text-align: center;
        text-decoration: none !important;
        color: inherit !important;
        transition: var(--transition);
        display: block;
    }
    .categoria-card:hover {
        transform: translateY(-6px);
        box-shadow: var(--shadow-hover);
        border-color: var(--color-accent);
    }
    .categoria-card .cat-icon {
        width: 56px; height: 56px;
        display: flex; align-items: center; justify-content: center;
        border-radius: var(--radius-md);
        margin: 0 auto .75rem;
        font-size: 1.3rem;
        background: var(--color-surface);
        color: var(--color-primary);
        transition: var(--transition);
    }
    .categoria-card:hover .cat-icon {
        background: var(--color-primary);
        color: var(--color-accent);
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
        transform: translateY(-6px);
        box-shadow: var(--shadow-hover);
    }
    .product-card .product-img {
        height: 220px;
        object-fit: cover;
        width: 100%;
        transition: transform .4s ease;
    }
    .product-card:hover .product-img { transform: scale(1.03); }
    .product-img-wrapper { overflow: hidden; position: relative; }

    /* ── Banner CTA ── */
    .banner-cta {
        background: linear-gradient(160deg, #1a1a2e 0%, #2a1f3d 100%);
        border-radius: var(--radius-xl);
        position: relative;
        overflow: hidden;
    }
    .banner-cta::before {
        content: '';
        position: absolute; inset: 0;
        background: radial-gradient(circle at 20% 50%, rgba(201,169,110,.1) 0%, transparent 50%);
    }

    /* ── Features / Ventajas ── */
    .feature-card {
        text-align: center;
        padding: 2rem 1rem;
    }
    .feature-icon-el {
        width: 56px; height: 56px;
        border-radius: var(--radius-md);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.2rem;
        margin: 0 auto 1rem;
        transition: var(--transition);
    }

    /* ── Barra de beneficios ── */
    .benefits-bar {
        background: var(--color-card);
        border-top: 1px solid var(--color-border);
        border-bottom: 1px solid var(--color-border);
    }
    .benefit-item {
        display: flex;
        align-items: center;
        gap: .5rem;
        justify-content: center;
        padding: .75rem 0;
    }
    .benefit-item i { color: var(--color-accent); }
    .benefit-item span { font-size: .8rem; font-weight: 500; color: var(--color-text-muted); }

    /* ── Sección headers elegantes ── */
    .section-label {
        display: inline-flex; align-items: center; gap: .35rem;
        font-size: .7rem; font-weight: 700;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        color: var(--color-accent);
        margin-bottom: .35rem;
    }
    .section-title {
        font-weight: 700;
        letter-spacing: -.5px;
        color: var(--color-primary);
    }
</style>
@endsection

@section('content')

{{-- ═══ HERO ═══ --}}
<section class="hero-home d-flex align-items-center text-white position-relative">
    <div class="container py-5 text-center position-relative" style="z-index:1;">
        <div class="animate-in" style="animation-delay:.1s;">
            <div class="hero-badge mb-4">
                <i class="fas fa-gem"></i>Más de 20 productos exclusivos
            </div>
        </div>
        <div class="animate-in" style="animation-delay:.2s;">
            <h1 class="hero-title mb-3">
                El mueble perfecto<br>
                <span>para cada espacio</span>
            </h1>
        </div>
        <div class="animate-in" style="animation-delay:.3s;">
            <p class="lead mb-4 mx-auto" style="max-width:480px;opacity:.6;font-size:1.05rem;font-weight:300;">
                Diseño, calidad y confort en una sola tienda. Encuentra el estilo que define tu hogar.
            </p>
        </div>
        <div class="d-flex gap-3 justify-content-center flex-wrap animate-in" style="animation-delay:.4s;">
            <a href="{{ route('muebles.index') }}" class="btn btn-accent btn-lg px-4">
                <i class="fas fa-arrow-right me-2"></i>Ver catálogo
            </a>
            @guest
                <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg px-4"
                   style="border-color:rgba(255,255,255,.2);">
                    Crear cuenta gratis
                </a>
            @endguest
        </div>
    </div>
</section>

{{-- ═══ BARRA DE BENEFICIOS ═══ --}}
<section class="benefits-bar">
    <div class="container">
        <div class="row g-0 text-center">
            <div class="col-6 col-md-3">
                <div class="benefit-item">
                    <i class="fas fa-shield-alt"></i>
                    <span>Calidad garantizada</span>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="benefit-item">
                    <i class="fas fa-truck"></i>
                    <span>Envío a toda España</span>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="benefit-item">
                    <i class="fas fa-undo-alt"></i>
                    <span>30 días de devolución</span>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="benefit-item">
                    <i class="fas fa-headset"></i>
                    <span>Atención 24/7</span>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ═══ CATEGORÍAS ═══ --}}
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
        <div class="d-flex align-items-end justify-content-between mb-4">
            <div>
                <div class="section-label"><i class="fas fa-th-large"></i>Categorías</div>
                <h2 class="section-title h4 mb-0">Explora por categoría</h2>
            </div>
            <a href="{{ route('muebles.index') }}" class="btn btn-outline-elegant btn-sm d-none d-md-inline-flex align-items-center gap-1">
                Ver todo <i class="fas fa-arrow-right" style="font-size:.7rem;"></i>
            </a>
        </div>
        <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 g-3">
            @foreach($categorias as $cat)
                <div class="col">
                    <a href="{{ route('muebles.index', ['categoria_id' => $cat['id']]) }}"
                       class="categoria-card h-100">
                        <div class="cat-icon">
                            <i class="fas {{ $catIcons[$cat['nombre']] ?? 'fa-cube' }}"></i>
                        </div>
                        <div class="fw-600" style="font-size:.9rem;">{{ $cat['nombre'] }}</div>
                        @if(!empty($cat['descripcion']))
                            <div class="text-muted mt-1" style="font-size:.72rem;">{{ Str::limit($cat['descripcion'], 40) }}</div>
                        @endif
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ═══ PRODUCTOS DESTACADOS ═══ --}}
@if(!empty($destacados))
<section class="py-5 bg-surface">
    <div class="container">
        <div class="d-flex align-items-end justify-content-between mb-4">
            <div>
                <div class="section-label"><i class="fas fa-star"></i>Selección especial</div>
                <h2 class="section-title h4 mb-0">Nuestras mejores piezas</h2>
            </div>
            <a href="{{ route('muebles.index') }}" class="btn btn-outline-elegant btn-sm d-none d-md-inline-flex align-items-center gap-1">
                Ver catálogo <i class="fas fa-arrow-right" style="font-size:.7rem;"></i>
            </a>
        </div>

        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
            @foreach($destacados as $m)
                <div class="col">
                    <div class="product-card h-100 d-flex flex-column">
                        <div class="product-img-wrapper">
                            @php
                                $imgUrl = null;
                                foreach ($m['galeria']['imagenes'] ?? [] as $img) {
                                    if ($img['es_principal']) { $imgUrl = $img['ruta']; break; }
                                }
                                if (!$imgUrl) $imgUrl = ($m['galeria']['imagenes'] ?? [])[0]['ruta'] ?? null;
                                if (!$imgUrl) $imgUrl = $m['imagen_principal'] ?? null;
                            @endphp
                            @if($imgUrl)
                                <img src="{{ asset('storage/' . $imgUrl) }}" class="product-img"
                                     alt="{{ $m['nombre'] }}">
                            @else
                                <div class="d-flex align-items-center justify-content-center" style="height:220px;background:var(--color-surface);">
                                    <i class="fas fa-couch fa-3x" style="color:var(--color-border);"></i>
                                </div>
                            @endif
                            <span class="position-absolute top-0 end-0 m-3" style="background:rgba(201,169,110,.9);color:var(--color-primary);font-size:.68rem;font-weight:700;padding:.3rem .6rem;border-radius:6px;">
                                <i class="fas fa-star me-1"></i>Destacado
                            </span>
                        </div>

                        <div class="p-3 d-flex flex-column flex-grow-1">
                            <h6 class="fw-600 mb-1" style="font-size:.92rem;">{{ $m['nombre'] }}</h6>
                            @if(!empty($m['categorias']))
                                <div class="mb-2">
                                    @foreach($m['categorias'] as $cat)
                                        <span style="font-size:.65rem;font-weight:500;color:var(--color-text-muted);background:var(--color-surface);padding:.15rem .45rem;border-radius:4px;">
                                            {{ $cat['nombre'] }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                            <p class="text-muted mb-2 flex-grow-1" style="font-size:.8rem;overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;">
                                {{ $m['descripcion'] ?? '' }}
                            </p>
                            <div class="d-flex align-items-center justify-content-between mt-auto pt-2" style="border-top:1px solid var(--color-border);">
                                <span class="fw-700" style="font-size:1.1rem;color:var(--color-primary);">{{ number_format($m['precio'], 2) }} €</span>
                                <span style="font-size:.7rem;font-weight:600;padding:.2rem .5rem;border-radius:4px;{{ ($m['stock'] ?? 0) > 0 ? 'background:rgba(45,138,78,.08);color:var(--color-success);' : 'background:rgba(192,57,43,.08);color:var(--color-danger);' }}">
                                    {{ ($m['stock'] ?? 0) > 0 ? 'En stock' : 'Agotado' }}
                                </span>
                            </div>
                        </div>

                        <div class="px-3 pb-3">
                            <a href="{{ route('muebles.show', $m['id']) }}" class="btn btn-elegant-dark btn-sm w-100">
                                Ver detalle
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="text-center mt-4 d-md-none">
            <a href="{{ route('muebles.index') }}" class="btn btn-outline-elegant">
                Ver catálogo completo <i class="fas fa-arrow-right ms-1"></i>
            </a>
        </div>
    </div>
</section>
@endif

{{-- ═══ BANNER CTA ═══ --}}
<section class="py-5">
    <div class="container">
        <div class="banner-cta text-white py-5 px-4 text-center">
            <div class="position-relative" style="z-index:1;">
                <h2 class="h3 fw-700 mb-2 ls-tight">¿No encuentras lo que buscas?</h2>
                <p class="mb-4" style="opacity:.5;font-weight:300;">Tenemos más de 20 piezas únicas esperándote.</p>
                <div class="d-flex gap-3 justify-content-center flex-wrap">
                    <a href="{{ route('muebles.index') }}" class="btn btn-accent px-4">
                        <i class="fas fa-search me-2"></i>Explorar catálogo
                    </a>
                    @guest
                        <a href="{{ route('register') }}" class="btn btn-outline-light px-4"
                           style="border-color:rgba(255,255,255,.2);">
                            Registrarse gratis
                        </a>
                    @endguest
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ═══ NOVEDADES ═══ --}}
@if(!empty($recientes))
<section class="py-5 bg-surface">
    <div class="container">
        <div class="mb-4">
            <div class="section-label"><i class="fas fa-clock"></i>Novedades</div>
            <h2 class="section-title h4 mb-0">Últimas incorporaciones</h2>
        </div>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-4">
            @foreach($recientes as $m)
                <div class="col">
                    <div class="product-card h-100 d-flex flex-column">
                        @php
                            $imgUrl = null;
                            foreach ($m['galeria']['imagenes'] ?? [] as $img) {
                                if ($img['es_principal']) { $imgUrl = $img['ruta']; break; }
                            }
                            if (!$imgUrl) $imgUrl = ($m['galeria']['imagenes'] ?? [])[0]['ruta'] ?? null;
                        @endphp
                        <div class="product-img-wrapper">
                            @if($imgUrl)
                                <img src="{{ asset('storage/' . $imgUrl) }}" class="product-img"
                                     alt="{{ $m['nombre'] }}" style="height:190px;">
                            @else
                                <div class="d-flex align-items-center justify-content-center" style="height:190px;background:var(--color-surface);">
                                    <i class="fas fa-couch fa-2x" style="color:var(--color-border);"></i>
                                </div>
                            @endif
                        </div>
                        <div class="p-3 flex-grow-1">
                            <h6 class="fw-600 mb-1" style="font-size:.9rem;">{{ $m['nombre'] }}</h6>
                            <div class="fw-700" style="color:var(--color-primary);">{{ number_format($m['precio'], 2) }} €</div>
                        </div>
                        <div class="px-3 pb-3">
                            <a href="{{ route('muebles.show', $m['id']) }}" class="btn btn-outline-elegant btn-sm w-100">
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

{{-- ═══ VENTAJAS ═══ --}}
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <div class="section-label justify-content-center"><i class="fas fa-gem"></i>Ventajas</div>
            <h2 class="section-title h4">¿Por qué elegirnos?</h2>
        </div>
        <div class="row g-4">
            <div class="col-sm-6 col-md-3">
                <div class="feature-card">
                    <div class="feature-icon-el" style="background:rgba(201,169,110,.1);color:var(--color-accent);">
                        <i class="fas fa-medal"></i>
                    </div>
                    <h6 class="fw-600 mb-2" style="font-size:.9rem;">Calidad premium</h6>
                    <p class="text-muted mb-0" style="font-size:.8rem;">Materiales seleccionados y acabados de primera calidad.</p>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="feature-card">
                    <div class="feature-icon-el" style="background:rgba(26,26,46,.06);color:var(--color-primary);">
                        <i class="fas fa-paint-brush"></i>
                    </div>
                    <h6 class="fw-600 mb-2" style="font-size:.9rem;">Diseño exclusivo</h6>
                    <p class="text-muted mb-0" style="font-size:.8rem;">Estilos contemporáneos, nórdicos e industriales.</p>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="feature-card">
                    <div class="feature-icon-el" style="background:rgba(45,138,78,.06);color:var(--color-success);">
                        <i class="fas fa-leaf"></i>
                    </div>
                    <h6 class="fw-600 mb-2" style="font-size:.9rem;">Sostenibilidad</h6>
                    <p class="text-muted mb-0" style="font-size:.8rem;">Madera certificada y procesos responsables.</p>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="feature-card">
                    <div class="feature-icon-el" style="background:rgba(192,57,43,.06);color:var(--color-danger);">
                        <i class="fas fa-tools"></i>
                    </div>
                    <h6 class="fw-600 mb-2" style="font-size:.9rem;">Montaje incluido</h6>
                    <p class="text-muted mb-0" style="font-size:.8rem;">Servicio profesional disponible en tu ciudad.</p>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
