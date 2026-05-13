@extends('layouts.app')

@section('title', 'Crear cuenta')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="text-center mb-4">
                <i class="fas fa-user-plus fa-3x text-dark mb-2"></i>
                <h2 class="fw-bold">Crear cuenta</h2>
                <p class="text-muted">Únete a MueblesHíbrido</p>
            </div>

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card shadow border-0 rounded-3">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('register.post') }}">
                        @csrf

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="nombre" class="form-label fw-semibold">Nombre</label>
                                <input type="text" id="nombre" name="nombre"
                                       class="form-control @error('nombre') is-invalid @enderror"
                                       value="{{ old('nombre') }}" required placeholder="Ana">
                                @error('nombre')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="apellidos" class="form-label fw-semibold">Apellidos</label>
                                <input type="text" id="apellidos" name="apellidos"
                                       class="form-control @error('apellidos') is-invalid @enderror"
                                       value="{{ old('apellidos') }}" required placeholder="García López">
                                @error('apellidos')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label for="email" class="form-label fw-semibold">Correo electrónico</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-envelope text-muted"></i></span>
                                    <input type="email" id="email" name="email"
                                           class="form-control @error('email') is-invalid @enderror"
                                           value="{{ old('email') }}" required placeholder="tu@email.com">
                                </div>
                                @error('email')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="password" class="form-label fw-semibold">Contraseña</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock text-muted"></i></span>
                                    <input type="password" id="password" name="password"
                                           class="form-control" required placeholder="Mín. 6 caracteres">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label fw-semibold">Confirmar contraseña</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock text-muted"></i></span>
                                    <input type="password" id="password_confirmation" name="password_confirmation"
                                           class="form-control" required placeholder="Repite la contraseña">
                                </div>
                            </div>

                            <div class="col-12 mt-2">
                                <button type="submit" class="btn btn-dark w-100 py-2">
                                    <i class="fas fa-user-plus me-2"></i>Crear cuenta
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <p class="text-center mt-4 text-muted">
                ¿Ya tienes cuenta?
                <a href="{{ route('login') }}" class="text-dark fw-semibold">Inicia sesión</a>
            </p>
            <p class="text-center">
                <a href="{{ route('muebles.index') }}" class="text-muted small">
                    <i class="fas fa-arrow-left me-1"></i>Volver al catálogo
                </a>
            </p>

        </div>
    </div>
</div>
@endsection
