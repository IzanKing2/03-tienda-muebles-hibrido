@extends('layouts.app')

@section('title', 'Crear cuenta')

@section('head')
<style>
    .auth-wrapper {
        min-height: calc(100vh - 200px);
        display: flex; align-items: center;
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
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        padding: 3rem 2rem; color: #fff;
        position: relative; overflow: hidden;
    }
    .auth-sidebar::before {
        content: '';
        position: absolute; inset: 0;
        background: radial-gradient(circle at 70% 30%, rgba(201,169,110,.1) 0%, transparent 60%);
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
                        {{-- Panel decorativo --}}
                        <div class="col-md-5 d-none d-md-flex auth-sidebar">
                            <div class="position-relative text-center">
                                <div style="width:64px;height:64px;border-radius:var(--radius-md);background:rgba(201,169,110,.15);display:flex;align-items:center;justify-content:center;margin:0 auto 1.5rem;">
                                    <i class="fas fa-user-plus fa-2x text-accent"></i>
                                </div>
                                <h3 class="fw-700 mb-2">Únete</h3>
                                <p style="opacity:.5;font-size:.85rem;font-weight:300;">Crea tu cuenta y disfruta de una experiencia de compra exclusiva.</p>
                                <div class="d-flex flex-column gap-2 mt-4" style="font-size:.78rem;opacity:.4;">
                                    <span><i class="fas fa-check me-2"></i>Guarda tus favoritos</span>
                                    <span><i class="fas fa-check me-2"></i>Historial de pedidos</span>
                                    <span><i class="fas fa-check me-2"></i>Envío gratuito</span>
                                </div>
                            </div>
                        </div>

                        {{-- Formulario --}}
                        <div class="col-md-7">
                            <div class="p-4 p-md-5">
                                <h2 class="fw-700 ls-tight mb-1" style="font-size:1.5rem;">Crear cuenta</h2>
                                <p class="text-muted mb-4" style="font-size:.85rem;">Completa tus datos para registrarte</p>

                                @if($errors->any())
                                    <div class="alert alert-danger py-2" style="font-size:.82rem;background:rgba(192,57,43,.06);color:var(--color-danger);border-left:3px solid var(--color-danger);border-radius:var(--radius-sm);">
                                        <ul class="mb-0 ps-3">
                                            @foreach($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <form method="POST" action="{{ route('register.post') }}">
                                    @csrf

                                    <div class="row g-3">
                                        <div class="col-sm-6">
                                            <label for="nombre" class="form-label fw-600" style="font-size:.85rem;">Nombre</label>
                                            <input type="text" id="nombre" name="nombre"
                                                   class="form-control @error('nombre') is-invalid @enderror"
                                                   value="{{ old('nombre') }}" required placeholder="Ana">
                                            @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>

                                        <div class="col-sm-6">
                                            <label for="apellidos" class="form-label fw-600" style="font-size:.85rem;">Apellidos</label>
                     