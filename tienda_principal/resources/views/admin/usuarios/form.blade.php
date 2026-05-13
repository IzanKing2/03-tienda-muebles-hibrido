@extends('layouts.app')

@section('title', isset($usuario) ? 'Editar usuario' : 'Nuevo usuario')

@section('content')
<div class="container py-5">

    <div class="mb-4">
        <a href="{{ route('admin.usuarios.index') }}" class="text-muted text-decoration-none small">
            <i class="fas fa-arrow-left me-1"></i>Volver a usuarios
        </a>
        <h1 class="h3 fw-bold mt-1">
            @if($usuario)
                <i class="fas fa-user-edit me-2"></i>Editar: {{ $usuario['nombre'] }} {{ $usuario['apellidos'] ?? '' }}
            @else
                <i class="fas fa-user-plus me-2"></i>Nuevo Usuario
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
        <div class="col-lg-7">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <form action="{{ $usuario ? route('admin.usuarios.update', $usuario['id']) : route('admin.usuarios.store') }}"
                          method="POST">
                        @csrf
                        @if($usuario)
                            @method('PUT')
                        @endif

                        <div class="row g-3">

                            {{-- Nombre y Apellidos --}}
                            <div class="col-sm-6">
                                <label class="form-label fw-semibold">Nombre <span class="text-danger">*</span></label>
                                <input type="text" name="nombre" maxlength="100"
                                       class="form-control @error('nombre') is-invalid @enderror"
                                       value="{{ old('nombre', $usuario['nombre'] ?? '') }}"
                                       required placeholder="Nombre">
                                @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label fw-semibold">Apellidos <span class="text-danger">*</span></label>
                                <input type="text" name="apellidos" maxlength="150"
                                       class="form-control @error('apellidos') is-invalid @enderror"
                                       value="{{ old('apellidos', $usuario['apellidos'] ?? '') }}"
                                       required placeholder="Apellidos">
                                @error('apellidos')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            {{-- Email --}}
                            <div class="col-12">
                                <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    <input type="email" name="email" maxlength="150"
                                           class="form-control @error('email') is-invalid @enderror"
                                           value="{{ old('email', $usuario['email'] ?? '') }}"
                                           required placeholder="correo@ejemplo.com">
                                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            {{-- Rol --}}
                            <div class="col-12">
                                <label class="form-label fw-semibold">Rol <span class="text-danger">*</span></label>
                                <select name="rol_id" class="form-select @error('rol_id') is-invalid @enderror" required>
                                    <option value="">Seleccionar rol…</option>
                                    @foreach($roles as $rol)
                                        <option value="{{ $rol['id'] }}"
                                            {{ old('rol_id', $usuario['rol']['id'] ?? '') == $rol['id'] ? 'selected' : '' }}>
                                            {{ $rol['nombre'] }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('rol_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            {{-- Contraseña --}}
                            <div class="col-sm-6">
                                <label class="form-label fw-semibold">
                                    Contraseña
                                    @if(!$usuario)<span class="text-danger">*</span>@else<span class="text-muted small">(dejar vacío para no cambiar)</span>@endif
                                </label>
                                <input type="password" name="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       placeholder="Mínimo 6 caracteres"
                                       {{ !$usuario ? 'required' : '' }}>
                                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label fw-semibold">Confirmar contraseña</label>
                                <input type="password" name="password_confirmation"
                                       class="form-control"
                                       placeholder="Repetir contraseña"
                                       {{ !$usuario ? 'required' : '' }}>
                            </div>

                        </div>

                        <hr class="my-4">

                        <div class="d-flex gap-3">
                            <button type="submit" class="btn btn-dark px-4">
                                <i class="fas fa-save me-2"></i>
                                {{ $usuario ? 'Guardar cambios' : 'Crear usuario' }}
                            </button>
                            <a href="{{ route('admin.usuarios.index') }}" class="btn btn-outline-secondary">
                                Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @if($usuario)
            <div class="col-lg-5 mt-4 mt-lg-0">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light fw-semibold small">Información actual</div>
                    <div class="card-body">
                        <dl class="row small mb-0">
                            <dt class="col-5 text-muted">ID</dt>
                            <dd class="col-7">{{ $usuario['id'] }}</dd>
                            <dt class="col-5 text-muted">Rol actual</dt>
                            <dd class="col-7">{{ $usuario['rol']['nombre'] ?? '—' }}</dd>
                            <dt class="col-5 text-muted">Registro</dt>
                            <dd class="col-7">
                                {{ isset($usuario['created_at']) ? \Carbon\Carbon::parse($usuario['created_at'])->format('d/m/Y H:i') : '—' }}
                            </dd>
                            <dt class="col-5 text-muted">Estado</dt>
                            <dd class="col-7">
                                @if($usuario['bloqueado_hasta'] ?? false)
                                    <span class="badge bg-danger">Bloqueado</span>
                                @else
                                    <span class="badge bg-success">Activo</span>
                                @endif
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        @endif
    </div>

</div>
@endsection
