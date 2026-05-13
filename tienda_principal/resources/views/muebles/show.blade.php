@extends('layouts.app')

@section('title', $mueble['nombre'] ?? 'Detalle del producto')

@section('content')
<div class="container py-5">

    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('muebles.index') }}" class="text-decoration-none text-muted">Catálogo</a></li>
            <li class="breadcrumb-item active">{{ $mueble['nombre'] }}</li>
        </ol>
    </nav>

    <div class="row g-4">

        {{-- Galería de imágenes --}}
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
                     class="img-fluid rounded-3 shadow w-100"
                     alt="{{ $mueble['nombre'] }}"
                     style="max-height:420px;object-fit:cover;"
                     id="imgPrincipal">
                @if(count($imagenes) > 1)
                    <div class="d-flex gap-2 mt-3 flex-wrap">
                        @foreach($imagenes as $img)
                            <img src="{{ asset('storage/' . $img['ruta']) }}"
                                 class="rounded border {{ $img['es_principal'] ? 'border-dark border-2' : '' }}"
                                 style="width:70px;height:70px;object-fit:cover;cursor:pointer;"
                                 onclick="document.getElementById('imgPrincipal').src=this.src"
                                 alt="Imagen del producto">
                        @endforeach
                    </div>
                @endif
            @else
                <div class="d-flex align-items-center justify-content-center bg-light rounded-3 shadow"
                     style="height:420px;">
                    <i class="fas fa-couch fa-5x text-secondary opacity-50"></i>
                </div>
            @endif
        </div>

        {{-- Información del producto --}}
        <div class="col-md-7">
            <div class="d-flex align-items-start justify-content-between mb-2">
                <h1 class="h2 fw-bold">{{ $mueble['nombre'] }}</h1>
                @if(isset($mueble['destacado']) && $mueble['destacado'])
                    <span class="badge bg-warning text-dark ms-2"><i class="fas fa-star me-1"></i>Destacado</span>
                @endif
            </div>

            {{-- Categorías --}}
            @if(!empty($mueble['categorias']))
                <div class="mb-3">
                    @foreach($mueble['categorias'] as $cat)
                        <span class="badge bg-dark bg-opacity-10 text-dark border me-1">{{ $cat['nombre'] }}</span>
                    @endforeach
                </div>
            @endif

            {{-- Precio y stock --}}
            <div class="d-flex align-items-center gap-3 mb-4">
                <span class="display-6 fw-bold text-dark">{{ number_format($mueble['precio'], 2) }} €</span>
                @if(isset($mueble['stock']))
                    @if($mueble['stock'] > 0)
                        <span class="badge bg-success fs-6"><i class="fas fa-check me-1"></i>En stock ({{ $mueble['stock'] }})</span>
                    @else
                        <span class="badge bg-danger fs-6"><i class="fas fa-times me-1"></i>Agotado</span>
                    @endif
                @endif
            </div>

            {{-- Descripción --}}
            @if(!empty($mueble['descripcion']))
                <p class="text-muted mb-4" style="line-height:1.7;">{{ $mueble['descripcion'] }}</p>
            @endif

            <hr>

            {{-- Características --}}
            <div class="row g-3 mb-4">
                @if(!empty($mueble['materiales']))
                    <div class="col-sm-6">
                        <div class="p-3 bg-light rounded-3">
                            <div class="small text-muted fw-semibold mb-1"><i class="fas fa-tree me-1"></i>Materiales</div>
                            <div>{{ $mueble['materiales'] }}</div>
                        </div>
                    </div>
                @endif
                @if(!empty($mueble['dimensiones']))
                    <div class="col-sm-6">
                        <div class="p-3 bg-light rounded-3">
                            <div class="small text-muted fw-semibold mb-1"><i class="fas fa-ruler-combined me-1"></i>Dimensiones</div>
                            <div>{{ $mueble['dimensiones'] }}</div>
                        </div>
                    </div>
                @endif
                @if(!empty($mueble['color_principal']))
                    <div class="col-sm-6">
                        <div class="p-3 bg-light rounded-3">
                            <div class="small text-muted fw-semibold mb-1"><i class="fas fa-palette me-1"></i>Color principal</div>
                            <div>{{ $mueble['color_principal'] }}</div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="d-flex gap-3 flex-wrap">
                <a href="{{ route('muebles.index') }}" class="btn btn-outline-dark">
                    <i class="fas fa-arrow-left me-1"></i>Volver al catálogo
                </a>
                @if(session('auth_token'))
                    {{-- Aquí se puede añadir lógica de carrito en el futuro --}}
                @else
                    <a href="{{ route('login') }}" class="btn btn-dark">
                        <i class="fas fa-sign-in-alt me-1"></i>Inicia sesión para comprar
                    </a>
                @endif
            </div>
        </div>
    </div>

</div>
@endsection
