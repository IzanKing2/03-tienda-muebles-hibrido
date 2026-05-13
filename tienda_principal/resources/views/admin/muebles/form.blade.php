@extends('layouts.app')

@section('title', isset($mueble) ? 'Editar producto' : 'Nuevo producto')

@section('content')
<div class="container py-5">

    <div class="mb-4">
        <a href="{{ route('admin.muebles.index') }}" class="text-muted text-decoration-none small">
            <i class="fas fa-arrow-left me-1"></i>Volver a productos
        </a>
        <h1 class="h3 fw-bold mt-1">
            @if($mueble)
                <i class="fas fa-edit me-2"></i>Editar: {{ $mueble['nombre'] }}
            @else
                <i class="fas fa-plus me-2"></i>Nuevo Producto
            @endif
        </h1>
    </div>

    @if($errors->any())
        <div class="alert alert-danger mb-4">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <form action="{{ $mueble ? route('admin.muebles.update', $mueble['id']) : route('admin.muebles.store') }}"
                          method="POST">
                        @csrf
                        @if($mueble)
                            @method('PUT')
                        @endif

                        <div class="row g-3">

                            {{-- Nombre --}}
                            <div class="col-12">
                                <label class="form-label fw-semibold">Nombre <span class="text-danger">*</span></label>
                                <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror"
                                       value="{{ old('nombre', $mueble['nombre'] ?? '') }}"
                                       placeholder="Ej: Sofá Nórdico 3 plazas" required maxlength="200">
                                @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            {{-- Descripción --}}
                            <div class="col-12">
                                <label class="form-label fw-semibold">Descripción <span class="text-danger">*</span></label>
                                <textarea name="descripcion" rows="4"
                                          class="form-control @error('descripcion') is-invalid @enderror"
                                          placeholder="Descripción detallada del producto…" required>{{ old('descripcion', $mueble['descripcion'] ?? '') }}</textarea>
                                @error('descripcion')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            {{-- Precio y Stock --}}
                            <div class="col-sm-6">
                                <label class="form-label fw-semibold">Precio (€) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-euro-sign"></i></span>
                                    <input type="number" name="precio" step="0.01" min="0"
                                           class="form-control @error('precio') is-invalid @enderror"
                                           value="{{ old('precio', $mueble['precio'] ?? '') }}"
                                           placeholder="0.00" required>
                                    @error('precio')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label fw-semibold">Stock <span class="text-danger">*</span></label>
                                <input type="number" name="stock" min="0"
                                       class="form-control @error('stock') is-invalid @enderror"
                                       value="{{ old('stock', $mueble['stock'] ?? 0) }}"
                                       required>
                                @error('stock')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            {{-- Materiales --}}
                            <div class="col-12">
                                <label class="form-label fw-semibold">Materiales</label>
                                <input type="text" name="materiales"
                                       class="form-control @error('materiales') is-invalid @enderror"
                                       value="{{ old('materiales', $mueble['materiales'] ?? '') }}"
                                       placeholder="Ej: Madera de roble y tela">
                                @error('materiales')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            {{-- Dimensiones y Color --}}
                            <div class="col-sm-6">
                                <label class="form-label fw-semibold">Dimensiones</label>
                                <input type="text" name="dimensiones" maxlength="100"
                                       class="form-control @error('dimensiones') is-invalid @enderror"
                                       value="{{ old('dimensiones', $mueble['dimensiones'] ?? '') }}"
                                       placeholder="Ej: 80x40x75 cm">
                                @error('dimensiones')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label fw-semibold">Color principal</label>
                                <input type="text" name="color_principal" maxlength="50"
                                       class="form-control @error('color_principal') is-invalid @enderror"
                                       value="{{ old('color_principal', $mueble['color_principal'] ?? '') }}"
                                       placeholder="Ej: Blanco, Nogal, Negro…">
                                @error('color_principal')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            {{-- Categorías --}}
                            @if(count($categorias) > 0)
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Categorías</label>
                                    @php
                                        $categoriasActuales = collect($mueble['categorias'] ?? [])->pluck('id')->toArray();
                                    @endphp
                                    <div class="row g-2">
                                        @foreach($categorias as $cat)
                                            <div class="col-sm-4 col-md-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox"
                                                           name="categorias[]"
                                                           value="{{ $cat['id'] }}"
                                                           id="cat_{{ $cat['id'] }}"
                                                           {{ in_array($cat['id'], old('categorias', $categoriasActuales)) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="cat_{{ $cat['id'] }}">
                                                        {{ $cat['nombre'] }}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            {{-- Destacado --}}
                            <div class="col-12">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox"
                                           name="destacado" id="destacado" value="1"
                                           {{ old('destacado', ($mueble['destacado'] ?? false) ? 1 : 0) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="destacado">
                                        <i class="fas fa-star text-warning me-1"></i>Producto destacado
                                    </label>
                                </div>
                            </div>

                        </div>

                        <hr class="my-4">

                        <div class="d-flex gap-3">
                            <button type="submit" class="btn btn-dark px-4">
                                <i class="fas fa-save me-2"></i>
                                {{ $mueble ? 'Guardar cambios' : 'Crear producto' }}
                            </button>
                            <a href="{{ route('admin.muebles.index') }}" class="btn btn-outline-secondary">
                                Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Panel lateral --}}
        <div class="col-lg-4 mt-4 mt-lg-0">
            @if($mueble)
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header bg-light fw-semibold small">Vista en tienda</div>
                    <div class="card-body">
                        <a href="{{ route('muebles.show', $mueble['id']) }}" target="_blank"
                           class="btn btn-outline-dark btn-sm w-100">
                            <i class="fas fa-eye me-1"></i>Ver producto
                        </a>
                    </div>
                </div>
            @endif
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light fw-semibold small">Ayuda</div>
                <div class="card-body small text-muted">
                    <ul class="mb-0 ps-3">
                        <li>El <strong>nombre</strong> y la <strong>descripción</strong> son obligatorios.</li>
                        <li>El <strong>precio</strong> debe ser mayor o igual a 0.</li>
                        <li>El <strong>stock</strong> controla la disponibilidad en el carrito.</li>
                        <li>Las imágenes se gestionan a través de la API directamente.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
