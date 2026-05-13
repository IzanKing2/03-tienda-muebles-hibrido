@extends('layouts.app')

@section('title', 'Iniciar sesión')

@section('head')
<style>
    /* ── Contenedor de autenticación con fondo decorativo ── */
    .auth-wrapper {
        min-height: calc(100vh - 200px);
        display: flex;
        align-items: center;
        position: relative;
    }
    .auth-card {
        background: var(--color-card);
        border: 1px solid var(--color-border);
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-lg);
        overflow: hidden;
    }
    .auth-card .form-control {
        border: 1.5px solid var(--color-border);
        border-radius: var(--radius-sm);
        padding: .65rem .85rem;
        font-size: .9rem;
        transition: var(--transition);
    }
    .auth-card .form-control:focus {
        border-color: var(--color-accent);
        box-shadow: 0 0 0 3px rgba(201,169,110,.1);
    }
    .auth-card .input-group-text {
        background: var(--color-surface);
        border: 1.5px solid var(--color-border);
        border-right: none;
        border-radius: var(--radius-sm) 0 0 var(--radius-sm);
        color: var(--color-text-muted);
    }
    .auth-card .input-group .form-control {
        border-left: none;
        border-radius: 0 var(--radius-sm) var(--radius-sm) 0;
    }
    .auth-sidebar {
        background: linear-gradient(160deg, #1a1a2e, #2a1f3d);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 3rem 2rem;
        color: #fff;
        position: relative;
        overflow: hidden;
    }
    .auth-sidebar::before {
        content: '';
        position: absolute; inset: 0;
        background: radial-gradient(circle at 30% 70%, rgba(201,169,110,.1) 0%, transparent 60%);
    }
</style>
@endsection

@section('content')
<div class="auth-wrapper">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-9">
                <div class="auth-card animate-in">
                    <div class="row g-0">
                        {{-- Panel decorativo lateral (solo en desktop) --}}
                        <div class="col-md-5 d-none d-md-flex auth-sidebar">
                            <div class="position-relative text-center">
                                <div style="width:64px;height:64px;border-radius:var(--radius-md);background:rgba(201,169,110,.15);display:flex;align-items:center;justify-content:center;margin:0 auto 1.5rem;">
                                    <i class="fas fa-couch fa-2x text-accent"></i>
                                </div>
                                <h3 class="fw-700 mb-2">Bienvenido</h3>
                                <p style="opacity:.5;font-size:.85rem;font-weight:300;">Accede a tu cuenta para gestionar tus pedidos y favoritos.</p>
                            </div>
                        </div>

                        {{-- Formulario --}}
                        <div class="col-md-7">
                            <div class="p-4 p-md-5">
                                <div class="text-center mb-4 d-md-none">
                                    <div style="width:48px;height:48px;border-radius:var(--radius-sm);background:var(--color-surface);display:inline-flex;align-items:center;justify-content:center;margin-bottom:.75rem;">
                                        <i class="fas fa-couch fa-lg" style="color:var(--color-primary);"></i>
                                    </div>
                                </div>

                                <h2 class="fw-700 ls-tight mb-1" style="font-size:1.5rem;">Iniciar sesión</h2>
                                <p class="text-muted mb-4" style="font-size:.85rem;">Introduce tus credenciales para acceder</p>

                                @if($errors->any())
                                    <div class="alert alert-danger d-flex align-items-center gap-2 py-2"
                                         style="font-size:.85rem;background:rgba(192,57,43,.06);color:var(--color-danger);border-left:3px solid var(--color-danger);border-radius:var(--radius-sm);">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $errors->first() }}
                                    </div>
                                @endif

                                <form method="POST" action="{{ route('login.post') }}">
                                    @csrf

                                    <div class="mb-3">
                                        <label for="email" class="form-label fw-600" style="font-size:.85rem;">Correo electrónico</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-envelope" style="font-size:.8rem;"></i></span>
                                            <input type="email" id="email" name="email"
                                                   class="form-control @error('email') is-invalid @enderror"
                                                   value="{{ old('email') }}" required autofocus
                                                   placeholder="tu@email.com">
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label for="password" class="form-label fw-600" style="font-size:.85rem;">Contraseña</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-lock" style="font-size:.8rem;"></i></span>
                                            <input type="password" id="password" name="password"
                                                   class="form-control" required placeholder="••••••••">
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-elegant-dark w-100 py-2" style="font-size:.9rem;">
                                        Entrar <i class="fas fa-arrow-right ms-2"></i>
                                    </button>
                                </form>

                                <div class="text-center mt-4">
                                    <p class="text-muted mb-1" style="font-size:.85rem;">
                                        ¿No tienes cuenta?
                                        <a href="{{ route('register') }}" class="fw-600 text-decoration-none" style="color:var(--color-accent);">Regístrate</a>
                                    </p>
                                    <a href="{{ route('muebles.index') }}" class="text-muted" style="font-size:.78rem;text-decoration:none;">
                                        <i class="fas fa-arrow-left me-1"></i>Volver al catálogo
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
