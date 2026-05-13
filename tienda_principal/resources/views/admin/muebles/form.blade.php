@extends('layouts.app')

@section('title', isset($mueble) ? 'Editar producto' : 'Nuevo producto')

@section('head')
<style>
    .form-card {
        background: var(--color-card);
        border: 1px solid var(--color-border);
        border-radius: var(--radius-lg);
        overflow: hidden;
    }
    .form-card .form-control,
    .form-card .form-select,
    .form-card textarea {
        border: 1.5px solid var(--color-border);
        border-radius: var(--radius-sm);
        font-size: .88rem;
        transition: var(--transition);
    }
    .form-card .form-control:focus,
    .form-card .form-select:focus,
    .form-card textarea:focus {
        border-color: var(--color-accent);
        box-shadow: 0 0 0 3px rgba(201,169,110,.1);
    }
    .form-card .input-group-text {
        background: var(--color-surface);
        border: 1.5px solid var(--color-border);
        border-right: none;
        color: var(--color-text-muted);
    }
    .help-card {
        background: var(--color-card);
        border: 1px solid var(--color-border);
        border-radius: var(--radius-lg);
        overflow: hidden;
    }
    .help-header {
        background: var(--color-surface);
        padding: .75rem 1rem;
        font-size: .82rem;
        font-weight: 600;
        border-bottom: 1px solid var(--color-border);
    }
</style>
@endsection

@section('content')
<div class="container py-5 animate-in">

    <div class="mb-4">
        <a href="{{ route('admin.muebles.index') }}" style="font-size:.82rem;color:var(--color-text-muted);text-decoration:none;">
            <i class="fas fa-arrow-left me-1"></i>Volver a productos
        </a>
        <h1 class="fw-700 ls-tight mt-1" style="font-size:1.5rem;">
            @if($mueble)
                <i class="fas fa-edit me-2" style="color:var(--color-accent);"></i>Editar: {{ $mueble['nombre'] }}
            @else
                <i class="fas fa-plus me-2" style="color:var(--color-accent);"></i>Nuevo Producto
            @endif
        </h1>
    </div>

    @if($errors->any())
        <div class="alert mb-4 py-2" style="font-size:.82rem;background:rgba(192,57,43,.06);color:var(--color-danger);border-left:3px solid var(--color-danger);border-radius:var(--radius-sm);">
            <ul class="mb-0 ps-3">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="form-card">
                <div class="p-4">
                    <form action="{{ $mueble ? route('admin.muebles.update', $mueble['id']) : route('admin.muebles.store') }}"
                          method="POST">
                        @csrf
                        @if($mueble) @method('PUT') @endif

                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-600" style="font-size:.82rem;">Nombre <span style="color:var(--color-danger);">*</span></label>
                                <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror"
                                       value="{{ old('nombre', $mueble['nombre'] ?? '') }}"
                                       placeholder="Ej: Sofá Nórdico 3 plazas" required maxlength="200">
                                @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-600" style="font-size:.82rem;">Descripción <span style="color:var(--color-danger);">*</span></label>
                                <textarea name="descripcion" rows="4" class="form-control @error('descripcion') is-invalid @enderror"
                                          placeholder="Descripción detallada…" required>{{ old('descripcion', $mueble['descripcion'] ?? '') }}</textarea>
                                @error('descripcion')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-sm-6">
                                <label class="form-label fw-600" style="font-size:.82rem;">Precio (€) <span style="color:var(--color-danger);">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-euro-sign" style="font-size:.8rem;"></i></span>
                                    <input type="number" name="precio" step="0.01" min="0"
                                           class="form-control @error('precio') is-invalid @enderror"
                                           value="{{ old('precio', $mueble['precio'] ?? '') }}" placeholder="0.00" required
                                           style="border-left:none;border-radius:0 var(--radius-sm) var(--radius-sm) 0;">
                                    @error('precio')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label fw-600" style="font-size:.82rem;">Stock <span style="color:var(--color-danger);">*</span></label>
                                <input type="number" name="stock" min="0"
                                       class="form-control @error('stock') is-invalid @enderror"
                                       value="{{ old('stock', $mueble['stock'] ?? 0) }}" required>
                                @error('stock')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-600" style="font-size:.82rem;">Materiales</label>
                                <input type="text" name="materiales" class="form-control @error('materiales') is-invalid @enderror"
                                       value="{{ old('materiales', $mueble['materiales'] ?? '') }}" placeholder="Ej: Madera de roble y tela">
                                @error('materiales')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-sm-6">
                                <label class="form-label fw-600" style="font-size:.82rem;">Dimensiones</label>
                                <input type="text" name="dimensiones" maxlength="100"
                                       class="form-control @error('dimensiones') is-invalid @enderror"
                                       value="{{ old('dimensiones', $mueble['dimensiones'] ?? '') }}" placeholder="Ej: 80x40x75 cm">
                                @error('dimensiones')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label fw-600" style="font-size:.82rem;">Color principal</label>
                                <input type="text" name="color_principal" maxlength="50"
                                       class="form-control @error('color_principal') is-invalid @enderror"
                                       value="{{ old('color_principal', $mueble['color_principal'] ?? '') }}" placeholder="Ej: Blanco, Nogal…">
                                @error('color_principal')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            @if(count($categorias) > 0)
                                <div class="col-12">
                                    <label class="form-label fw-600" style="font-size:.82rem;">Categorías</label>
                                    @php $categoriasActuales = collect($mueble['categorias'] ?? [])->pluck('id')->toArray(); @endphp
                                    <div class="row g-2">
                                        @foreach($categorias as $cat)
                                            <div class="col-sm-4 col-md-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="categorias[]"
                                                           value="{{ $cat['id'] }}" id="cat_{{ $cat['id'] }}"
                                                           {{ in_array($cat['id'], old('categorias', $categoriasActuales)) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="cat_{{ $cat['id'] }}" style="font-size:.85rem;">
                                                        {{ $cat['nombre'] }}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <div class="col-12">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="destacado" id="destacado" value="1"
                                           {{ old('destacado', ($mueble['destacado'] ?? false) ? 1 : 0) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="destacado" style="font-size:.88rem;">
                                        <i class="fas fa-star me-1" style="color:var(--color-accent);"></i>Producto destacado
                                    </label>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4" style="border-color:var(--color-border);">

                        <div class="d-flex gap-3">
                            <button type="submit" class="btn btn-elegant-dark px-4">
                                <i class="fas fa-save me-2"></i>{{ $mueble ? 'Guardar cambios' : 'Crear producto' }}
                            </button>
                            <a href="{{ route('admin.muebles.index') }}" class="btn btn-outline-elegant">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mt-4 mt-lg-0">
            @if($mueble)
                <div class="help-card mb-3">
                    <div class="help-header">Vista en tienda</div>
                    <div class="p-3">
                        <a href="{{ route('muebles.show', $mueble['id']) }}" target="_blank" class="btn btn-outline-elegant btn-sm w-100">
                            <i class="fas fa-eye me-1"></i>Ver producto
                        </a>
                    </div>
                </div>
            @endif
            <div class="help-card">
                <div class="help-header">Ayuda</div>
                <div class="p-3" style="font-size:.82rem;color:var(--color-text-muted);">
                    <ul class="mb-0 ps-3" style="line-height:1.8;">
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
