@extends('layouts.app')

@section('title', isset($usuario) ? 'Editar usuario' : 'Nuevo usuario')

@section('head')
<style>
    .form-card {
        background: var(--color-card);
        border: 1px solid var(--color-border);
        border-radius: var(--radius-lg);
    }
    .form-card .form-control,
    .form-card .form-select {
        border: 1.5px solid var(--color-border);
        border-radius: var(--radius-sm);
        font-size: .88rem;
        transition: var(--transition);
    }
    .form-card .form-control:focus,
    .form-card .form-select:focus {
        border-color: var(--color-accent);
        box-shadow: 0 0 0 3px rgba(201,169,110,.1);
    }
    .form-card .input-group-text {
        background: var(--color-surface);
        border: 1.5px solid var(--color-border);
        border-right: none;
        color: var(--color-text-muted);
    }
    .info-card {
        background: var(--color-card);
        border: 1px solid var(--color-border);
        border-radius: var(--radius-lg);
        overflow: hidden;
    }
    .info-header {
        background: var(--color-surface);
        padding: .75rem 1rem;
        font-size: .82rem;
        font-weight: 600;
        border-bottom: 1px solid var(--color-border);
    }
    .info-label { font-size: .72rem; text-transform: uppercase; letter-spacing: .5px; font-weight: 600; color: var(--color-text-muted); }
</style>
@endsection

@section('content')
<div class="container py-5 animate-in">
    <div class="mb-4">
        <a href="{{ route('admin.usuarios.index') }}" style="font-size:.82rem;color:var(--color-text-muted);text-decoration:none;">
            <i class="fas fa-arrow-left me-1"></i>Volver a usuarios
        </a>
        <h1 class="fw-700 ls-tight mt-1" style="font-size:1.5rem;">
            @if($usuario)
                <i class="fas fa-user-edit me-2" style="color:var(--color-accent);"></i>Editar: {{ $usuario['nombre'] }} {{ $usuario['apellidos'] ?? '' }}
            @else
                <i class="fas fa-user-plus me-2" style="color:var(--color-accent);"></i>Nuevo Usuario
            @endif
        </h1>
    </div>

    @if($errors->any())
        <div class="alert mb-4 py-2" style="font-size:.82rem;background:rgba(192,57,43,.06);color:var(--color-danger);border-left:3px solid var(--color-danger);border-radius:var(--radius-sm);">
            <ul class="mb-0 ps-3">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-7">
            <div class="form-card p-4">
                <form action="{{ $usuario ? route('admin.usuarios.update', $usuario['id']) : route('admin.usuarios.store') }}" method="POST">
                    @csrf
                    @if($usuario) @method('PUT') @endif

                    <div class="row g-3">
                        <div class="col-sm-6">
                            <label class="form-label fw-600" style="font-size:.82rem;">Nombre <span style="color:var(--color-danger);">*</span></label>
                            <input type="text" name="nombre" maxlength="100" class="form-control @error('nombre') is-invalid @enderror"
                                   value="{{ old('nombre', $usuario['nombre'] ?? '') }}" required placeholder="Nombre">
                            @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-600" style="font-size:.82rem;">Apellidos <span style="color:var(--color-danger);">*</span></label>
                            <input type="text" name="apellidos" maxlength="150" class="form-control @error('apellidos') is-invalid @enderror"
                                   value="{{ old('apellidos', $usuario['apellidos'] ?? '') }}" required placeholder="Apellidos">
                            @error('apellidos')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-600" style="font-size:.82rem;">Email <span style="color:var(--color-danger);">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-envelope" style="font-size:.8rem;"></i></span>
                                <input type="email" name="email" maxlength="150" class="form-control @error('email') is-invalid @enderror"
                                       value="{{ old('email', $usuario['email'] ?? '') }}" required placeholder="correo@ejemplo.com"
                                       style="border-left:none;border-radius:0 var(--radius-sm) var(--radius-sm) 0;">
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-600" style="font-size:.82rem;">Rol <span style="color:var(--color-danger);">*</span></label>
                            <select name="rol_id" class="form-select @error('rol_id') is-invalid @enderror" required>
                                <option value="">Seleccionar rol…</option>
                                @foreach($roles as $rol)
                                    <option value="{{ $rol['id'] }}" {{ old('rol_id', $usuario['rol']['id'] ?? '') == $rol['id'] ? 'selected' : '' }}>
                                        {{ $rol['nombre'] }}
                                    </option>
                                @endforeach
                            </select>
                            @error('rol_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-600" style="font-size:.82rem;">
                                Contraseña @if(!$usuario)<span style="color:var(--color-danger);">*</span>@else<span style="font-size:.72rem;color:var(--color-text-muted);font-weight:400;">(vacío = sin cambiar)</span>@endif
                            </label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                                   placeholder="Mínimo 6 caracteres" {{ !$usuario ? 'required' : '' }}>
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-600" style="font-size:.82rem;">Confirmar</label>
                            <input type="password" name="password_confirmation" class="form-control"
                                   placeholder="Repetir contraseña" {{ !$usuario ? 'required' : '' }}>
                        </div>
                    </div>

                    <hr class="my-4" style="border-color:var(--color-border);">

                    <div class="d-flex gap-3">
                        <button type="submit" class="btn btn-elegant-dark px-4">
                            <i class="fas fa-save me-2"></i>{{ $usuario ? 'Guardar cambios' : 'Crear usuario' }}
                        </button>
                        <a href="{{ route('admin.usuarios.index') }}" class="btn btn-outline-elegant">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>

        @if($usuario)
            <div class="col-lg-5 mt-4 mt-lg-0">
                <div class="info-card">
                    <div class="info-header">Información actual</div>
                    <div class="p-3">
                        <div class="mb-2"><span class="info-label">ID</span><div style="font-size:.88rem;">{{ $usuario['id'] }}</div></div>
                        <div class="mb-2"><span class="info-label">Rol actual</span><div style="font-size:.88rem;">{{ $usuario['rol']['nombre'] ?? '—' }}</div></div>
                        <div class="mb-2"><span class="info-label">Registro</span><div style="font-size:.88rem;">{{ isset($usuario['created_at']) ? \Carbon\Carbon::parse($usuario['created_at'])->format('d/m/Y H:i') : '—' }}</div></div>
                        <div><span class="info-label">Estado</span>
                            <div>
                                @if($usuario['bloqueado_hasta'] ?? false)
                                    <span style="font-size:.72rem;font-weight:600;padding:.2rem .5rem;border-radius:50px;background:rgba(192,57,43,.08);color:var(--color-danger);">Bloqueado</span>
                                @else
                                    <span style="font-size:.72rem;font-weight:600;padding:.2rem .5rem;border-radius:50px;background:rgba(45,138,78,.08);color:var(--color-success);">Activo</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
