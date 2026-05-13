@extends('layouts.app')

@section('title', 'Mi Perfil')

@section('head')
<style>
    .profile-card {
        background: var(--color-card);
        border: 1px solid var(--color-border);
        border-radius: var(--radius-xl);
        overflow: hidden;
    }
    .profile-header {
        background: linear-gradient(160deg, #1a1a2e, #2a1f3d);
        padding: 2.5rem 2rem 2rem;
        position: relative;
    }
    .profile-header::before {
        content: '';
        position: absolute; inset: 0;
        background: radial-gradient(circle at 80% 20%, rgba(201,169,110,.1) 0%, transparent 50%);
    }
    .profile-avatar {
        width: 80px; height: 80px;
        border-radius: var(--radius-md);
        display: flex; align-items: center; justify-content: center;
        font-size: 2rem; font-weight: 800;
        background: linear-gradient(135deg, var(--color-accent), var(--color-accent-hover));
        color: var(--color-primary);
        border: 3px solid rgba(255,255,255,.2);
    }
    .info-label {
        font-size: .68rem; text-transform: uppercase; letter-spacing: 1px;
        font-weight: 700; color: var(--color-text-muted); margin-bottom: .25rem;
    }
</style>
@endsection

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-7">
            <div class="profile-card animate-in">
                {{-- Cabecera con gradiente --}}
                <div class="profile-header">
                    <div class="d-flex align-items-center gap-3 position-relative">
                        <div class="profile-avatar">
                            {{ strtoupper(substr($usuario['nombre'], 0, 1)) }}
                        </div>
                        <div class="text-white">
                            <h2 class="fw-700 mb-1" style="font-size:1.3rem;">{{ $usuario['nombre'] }} {{ $usuario['apellidos'] }}</h2>
                            <span style="font-size:.72rem;font-weight:600;padding:.25rem .65rem;border-radius:50px;background:rgba(201,169,110,.15);color:var(--color-accent);">
                                {{ $usuario['rol']['nombre'] }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Datos --}}
                <div class="p-4 p-md-5">
                    <div class="row g-4">
                        <div class="col-sm-6">
                            <div class="info-label">Correo Electrónico</div>
                            <p class="mb-0" style="font-size:1rem;">{{ $usuario['email'] }}</p>
                        </div>
                        <div class="col-sm-6">
                            <div class="info-label">Miembro desde</div>
                            <p class="mb-0" style="font-size:1rem;">{{ date('d/m/Y', strtotime($usuario['created_at'])) }}</p>
                        </div>
                    </div>

                    <hr class="my-4" style="border-color:var(--color-border);">

                    <div class="d-flex gap-2 flex-wrap">
                        <a href="{{ route('pedidos.index') }}" class="btn btn-outline-elegant">
                            <i class="fas fa-box me-1"></i>Mis pedidos
                        </a>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn" style="border:1.5px solid rgba(192,57,43,.2);color:var(--color-danger);border-radius:var(--radius-sm);">
                                <i class="fas fa-sign-out-alt me-1"></i>Cerrar sesión
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
