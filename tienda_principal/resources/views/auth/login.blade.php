@extends('layouts.app')

@section('title', 'Iniciar sesión')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">

            <div class="text-center mb-4">
                <i class="fas fa-couch fa-3x text-dark mb-2"></i>
                <h2 class="fw-bold">Iniciar sesión</h2>
                <p class="text-muted">Accede a tu cuenta de MueblesHíbrido</p>
            </div>

            @if($errors->any())
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ $errors->first() }}
                </div>
            @endif

            <div class="card shadow border-0 rounded-3">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('login.post') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">Correo electrónico</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-envelope text-muted"></i></span>
                                <input type="email" id="email" name="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       value="{{ old('email') }}" required autofocus
                                       placeholder="tu@email.com">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label fw-semibold">Contraseña</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock text-muted"></i></span>
                                <input type="password" id="password" name="password"
                                       class="form-control" required placeholder="••••••">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-dark w-100 py-2">
                            <i class="fas fa-sign-in-alt me-2"></i>Entrar
                        </button>
                    </form>
                </div>
            </div>

            <p class="text-center mt-4 text-muted">
                ¿No tienes cuenta?
                <a href="{{ route('register') }}" class="text-dark fw-semibold">Regístrate</a>
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
