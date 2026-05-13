@extends('layouts.app')

@section('title', $mueble['nombre'] ?? 'Detalle del producto')

@section('head')
<style>
    .product-main-img {
        border-radius: var(--radius-lg);
        max-height: 450px;
        object-fit: cover;
        width: 100%;
        transition: var(--transition);
    }
    .product-thumb {
        width: 72px; height: 72px;
        object-fit: cover;
        border-radius: var(--radius-sm);
        border: 2px solid var(--color-border);
        cursor: pointer;
        transition: var(--transition);
        opacity: .7;
    }
    .product-thumb:hover,
    .product-thumb.active { border-color: var(--color-accent); opacity: 1; }
    .spec-card {
        background: var(--color-surface);
        border-radius: var(--radius-md);
        padding: 1rem 1.2rem;
        border: 1px solid var(--color-border);
    }
    .spec-card i { color: var(--color-accent); }
    .breadcrumb-item a {
        color: var(--color-text-muted);
        text-decoration: none;
        font-size: .85rem;
        transition: var(--transition);
    }
    .breadcrumb-item a:hover { color: var(--color-accent); }
    .breadcrumb-item.active { font-size: .85rem; color: var(--color-text); font-weight: 500; }
</style>
@endsection

@section('content')
<div class="container py-5 animate-in">

    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('muebles.index') }}">Catálogo</a></li>
            <li class="breadcrumb-item active">{{ $mueble['nombre'] }}</li>
        </ol>
    </nav>

    <div class="row g-5">

        {{-- Galería --}}
        <div class="col-md-5">
            @php
                $imagenes = $mueble['galeria']['imagenes'] ?? [];
                $principal = null;
                foreach ($imagenes as $img) {
                    if ($img['es_principal']) { $principal = $img; break; }
                }
                if (!$principal && count($imagenes)) $principal = $imagenes[0];
            @endphp

            @if($principal)
                <img src="{{ asset('storage/' . $principal['ruta']) }}"
                     class="product-main-img shadow-sm w-100"
                     alt="{{ $mueble['nombre'] }}" id="imgPrincipal">
                @if(count($imagenes) > 1)
                    <div class="d-flex gap-2 mt-3 flex-wrap">
                        @foreach($imagenes as $img)
                            <img src="{{ asset('storage/' . $img['ruta']) }}"
                                 class="product-thumb {{ $img['es_principal'] ? 'active' : '' }}"
                                 onclick="document.getElementById('imgPrincipal').src=this.src; document.querySelectorAll('.product-thumb').forEach(t=>t.classList.remove('active')); this.classList.add('active');"
                                 alt="Imagen del producto">
                        @endforeach
                    </div>
                @endif
            @else
                <div class="d-flex align-items-center justify-content-center rounded-3" style="height:450px;background:var(--color-surface);border:1px solid var(--color-border);">
                    <i class="fas fa-couch fa-5x" style="color:var(--color-border);"></i>
                </div>
            @endif
        </div>

        {{-- Info --}}
        <div class="col-md-7">
            <div class="d-flex align-items-start justify-content-between mb-2 gap-2">
                <h1 class="fw-700 ls-tight" style="font-size:1.7rem;">{{ $mueble['nombre'] }}</h1>
                @if(isset($mueble['destacado']) && $mueble['destacado'])
                    <span style="background:rgba(201,169,110,.12);color:var(--color-accent);font-size:.72rem;font-weight:700;padding:.3rem .7rem;border-radius:6px;white-space:nowrap;">
                        <i class="fas fa-star me-1"></i>Destacado
                    </span>
                @endif
            </div>

            @if(!empty($mueble['categorias']))
                <div class="mb-3">
                    @foreach($mueble['categorias'] as $cat)
                        <span style="font-size:.72rem;font-weight:500;color:var(--color-text-muted);background:var(--color-surface);padding:.25rem .6rem;border-radius:5px;border:1px solid var(--color-border);">
                            {{ $cat['nombre'] }}
                        </span>
                    @endforeach
                </div>
            @endif

            <div class="d-flex align-items-center gap-3 mb-4">
                <span class="fw-800" style="font-size:2rem;color:var(--color-primary);">{{ number_format($mueble['precio'], 2) }} €</span>
                @if(isset($mueble['stock']))
                    @if($mueble['stock'] > 0)
                        <span style="font-size:.78rem;font-weight:600;padding:.35rem .7rem;border-radius:6px;background:rgba(45,138,78,.08);color:var(--color-success);">
                            <i class="fas fa-check me-1"></i>En stock ({{ $mueble['stock'] }})
                        </span>
                    @else
                        <span style="font-size:.78rem;font-weight:600;padding:.35rem .7rem;border-radius:6px;background:rgba(192,57,43,.08);color:var(--color-danger);">
                            <i class="fas fa-times me-1"></i>Agotado
                        </span>
                    @endif
                @endif
            </div>

            @if(!empty($mueble['descripcion']))
                <p style="color:var(--color-text-muted);line-height:1.8;font-size:.92rem;">{{ $mueble['descripcion'] }}</p>
            @endif

            <hr style="border-color:var(--color-border);">

            {{-- Características --}}
            <div class="row g-3 mb-4">
                @if(!empty($mueble['materiales']))
                    <div class="col-sm-6">
                        <div class="spec-card">
                            <div class="fw-600 mb-1" style="font-size:.75rem;color:var(--color-text-muted);"><i class="fas fa-tree me-1"></i>Materiales</div>
                            <div style="font-size:.88rem;">{{ $mueble['materiales'] }}</div>
                        </div>
                    </div>
                @endif
                @if(!empty($mueble['dimensiones']))
                    <div class="col-sm-6">
                        <div class="spec-card">
                            <div class="fw-600 mb-1" style="font-size:.75rem;color:var(--color-text-muted);"><i class="fas fa-ruler-combined me-1"></i>Dimensiones</div>
                            <div style="font-size:.88rem;">{{ $mueble['dimensiones'] }}</div>
                        </div>
                    </div>
                @endif
                @if(!empty($mueble['color_principal']))
                    <div class="col-sm-6">
                        <div class="spec-card">
                            <div class="fw-600 mb-1" style="font-size:.75rem;color:var(--color-text-muted);"><i class="fas fa-palette me-1"></i>Color principal</div>
                            <div style="font-size:.88rem;">{{ $mueble['color_principal'] }}</div>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Acciones --}}
            <div class="d-flex gap-3 flex-wrap align-items-center">
                <a href="{{ route('muebles.index') }}" class="btn btn-outline-elegant">
                    <i class="fas fa-arrow-left me-1"></i>Catálogo
                </a>

                @if(session('auth_token'))
                    @if(($mueble['stock'] ?? 0) > 0)
                        <form action="{{ route('carrito.agregar') }}" method="POST" class="d-flex gap-2 align-items-center">
                            @csrf
                            <input type="hidden" name="producto_id" value="{{ $mueble['id'] }}">
                            <input type="number" name="cantidad" value="1" min="1" max="{{ $mueble['stock'] }}"
                                   class="form-control" style="width:70px;border:1.5px solid var(--color-border);border-radius:var(--radius-sm);" aria-label="Cantidad">
                            <button type="submit" class="btn btn-accent fw-600">
                                <i class="fas fa-cart-plus me-1"></i>Añadir al carrito
                            </button>
                        </form>
                    @else
                        <button class="btn btn-outline-elegant" disabled style="opacity:.5;">
                            <i class="fas fa-times me-1"></i>Sin stock
                        </button>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="btn btn-elegant-dark">
                        <i class="fas fa-sign-in-alt me-1"></i>Inicia sesión para comprar
                    </a>
                @endif
            </div>
        </div>
    </div>

</div>
@endsection
