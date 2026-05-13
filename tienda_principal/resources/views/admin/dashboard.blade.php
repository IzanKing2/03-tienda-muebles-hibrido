@extends('layouts.app')

@section('title', 'Panel de Administración')

@section('head')
<style>
    .stat-card {
        background: var(--color-card);
        border: 1px solid var(--color-border);
        border-radius: var(--radius-lg);
        overflow: hidden;
        transition: var(--transition);
        position: relative;
    }
    .stat-card:hover { box-shadow: var(--shadow-md); transform: translateY(-2px); }
    .stat-card .stat-accent {
        position: absolute; left: 0; top: 0; bottom: 0; width: 4px;
    }
    .stat-icon {
        width: 52px; height: 52px;
        border-radius: var(--radius-md);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.2rem;
    }
    .quick-action {
        display: inline-flex; align-items: center; gap: .5rem;
        padding: .55rem 1rem;
        border-radius: var(--radius-sm);
        font-size: .85rem;
        font-weight: 500;
        text-decoration: none;
        transition: var(--transition);
    }
</style>
@endsection

@section('content')
<div class="container py-5 animate-in">

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <div class="section-label" style="font-size:.68rem;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;color:var(--color-accent);display:flex;align-items:center;gap:.35rem;">
                <i class="fas fa-tachometer-alt"></i>Administración
            </div>
            <h1 class="fw-700 ls-tight mb-0" style="font-size:1.5rem;">Panel de Control</h1>
        </div>
        <span style="background:var(--color-surface);border:1px solid var(--color-border);border-radius:var(--radius-sm);padding:.4rem .8rem;font-size:.82rem;font-weight:500;">
            {{ session('auth_user')['nombre'] ?? '' }}
            <span style="font-size:.68rem;font-weight:600;padding:.15rem .45rem;border-radius:4px;background:rgba(201,169,110,.12);color:var(--color-accent);margin-left:.3rem;">
                {{ session('auth_user')['rol']['nombre'] ?? 'Admin' }}
            </span>
        </span>
    </div>

    {{-- Estadísticas --}}
    <div class="row g-4 mb-5">
        <div class="col-sm-6 col-md-4">
            <div class="stat-card p-4">
                <div class="stat-accent" style="background:var(--color-accent);"></div>
                <div class="d-flex align-items-center gap-3 ms-2">
                    <div class="stat-icon" style="background:rgba(201,169,110,.1);color:var(--color-accent);">
                        <i class="fas fa-couch"></i>
                    </div>
                    <div>
                        <div class="fw-800" style="font-size:1.8rem;line-height:1;">{{ $stats['total_productos'] }}</div>
                        <div style="font-size:.78rem;color:var(--color-text-muted);font-weight:500;">Productos</div>
                    </div>
                </div>
                <a href="{{ route('admin.muebles.index') }}" class="btn btn-outline-elegant btn-sm w-100 mt-3">
                    Gestionar <i class="fas fa-arrow-right ms-1" style="font-size:.65rem;"></i>
                </a>
            </div>
        </div>

        <div class="col-sm-6 col-md-4">
            <div class="stat-card p-4">
                <div class="stat-accent" style="background:var(--color-primary);"></div>
                <div class="d-flex align-items-center gap-3 ms-2">
                    <div class="stat-icon" style="background:rgba(26,26,46,.06);color:var(--color-primary);">
                        <i class="fas fa-users"></i>
                    </div>
                    <div>
                        <div class="fw-800" style="font-size:1.8rem;line-height:1;">{{ $stats['total_usuarios'] }}</div>
                        <div style="font-size:.78rem;color:var(--color-text-muted);font-weight:500;">Usuarios</div>
                    </div>
                </div>
                <a href="{{ route('admin.usuarios.index') }}" class="btn btn-outline-elegant btn-sm w-100 mt-3">
                    Gestionar <i class="fas fa-arrow-right ms-1" style="font-size:.65rem;"></i>
                </a>
            </div>
        </div>

        <div class="col-sm-6 col-md-4">
            <div class="stat-card p-4">
                <div class="stat-accent" style="background:var(--color-success);"></div>
                <div class="d-flex align-items-center gap-3 ms-2">
                    <div class="stat-icon" style="background:rgba(45,138,78,.06);color:var(--color-success);">
                        <i class="fas fa-eye"></i>
                    </div>
                    <div>
                        <div class="fw-700" style="font-size:1.1rem;">Catálogo público</div>
                        <div style="font-size:.78rem;color:var(--color-text-muted);font-weight:500;">Vista de clientes</div>
                    </div>
                </div>
                <a href="{{ route('muebles.index') }}" target="_blank" class="btn btn-outline-elegant btn-sm w-100 mt-3">
                    Ver tienda <i class="fas fa-external-link-alt ms-1" style="font-size:.65rem;"></i>
                </a>
            </div>
        </div>
    </div>

    {{-- Acciones rápidas --}}
    <h5 class="fw-700 mb-3" style="font-size:1rem;">Acciones rápidas</h5>
    <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('admin.muebles.create') }}" class="quick-action btn-elegant-dark" style="color:#fff;">
            <i class="fas fa-plus"></i>Nuevo producto
        </a>
        <a href="{{ route('admin.usuarios.create') }}" class="quick-action btn-outline-elegant">
            <i class="fas fa-user-plus"></i>Nuevo usuario
        </a>
        <a href="{{ route('admin.muebles.index') }}" class="quick-action btn-outline-elegant">
            <i class="fas fa-list"></i>Lista de productos
        </a>
        <a href="{{ route('admin.usuarios.index') }}" class="quick-action btn-outline-elegant">
            <i class="fas fa-users"></i>Lista de usuarios
        </a>
    </div>

</div>
@endsection
