@extends('layouts.app')

@section('title', 'Panel de Administración')

@section('content')
<div class="container py-5">

    <div class="d-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 fw-bold mb-0"><i class="fas fa-tachometer-alt me-2"></i>Panel de Administración</h1>
        <span class="badge bg-dark fs-6">
            {{ session('auth_user')['nombre'] ?? '' }}
            <span class="badge bg-warning text-dark ms-1">
                {{ session('auth_user')['rol']['nombre'] ?? 'Admin' }}
            </span>
        </span>
    </div>

    {{-- Tarjetas de estadísticas --}}
    <div class="row g-4 mb-5">
        <div class="col-sm-6 col-md-4">
            <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #5c4033 !important;">
                <div class="card-body d-flex align-items-center gap-4">
                    <div class="rounded-circle d-flex align-items-center justify-content-center bg-warning bg-opacity-10"
                         style="width:60px;height:60px;">
                        <i class="fas fa-couch fa-2x text-warning"></i>
                    </div>
                    <div>
                        <div class="h2 fw-bold mb-0">{{ $stats['total_productos'] }}</div>
                        <div class="text-muted small">Productos en catálogo</div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 pt-0">
                    <a href="{{ route('admin.muebles.index') }}" class="btn btn-sm btn-outline-dark w-100">
                        <i class="fas fa-arrow-right me-1"></i>Gestionar productos
                    </a>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-md-4">
            <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #2c2c2c !important;">
                <div class="card-body d-flex align-items-center gap-4">
                    <div class="rounded-circle d-flex align-items-center justify-content-center bg-dark bg-opacity-10"
                         style="width:60px;height:60px;">
                        <i class="fas fa-users fa-2x text-dark"></i>
                    </div>
                    <div>
                        <div class="h2 fw-bold mb-0">{{ $stats['total_usuarios'] }}</div>
                        <div class="text-muted small">Usuarios registrados</div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 pt-0">
                    <a href="{{ route('admin.usuarios.index') }}" class="btn btn-sm btn-outline-dark w-100">
                        <i class="fas fa-arrow-right me-1"></i>Gestionar usuarios
                    </a>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-md-4">
            <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #198754 !important;">
                <div class="card-body d-flex align-items-center gap-4">
                    <div class="rounded-circle d-flex align-items-center justify-content-center bg-success bg-opacity-10"
                         style="width:60px;height:60px;">
                        <i class="fas fa-eye fa-2x text-success"></i>
                    </div>
                    <div>
                        <div class="h5 fw-bold mb-0">Catálogo público</div>
                        <div class="text-muted small">Vista de clientes</div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 pt-0">
                    <a href="{{ route('muebles.index') }}" target="_blank" class="btn btn-sm btn-outline-success w-100">
                        <i class="fas fa-external-link-alt me-1"></i>Ver tienda
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Acciones rápidas --}}
    <h5 class="fw-semibold mb-3">Acciones rápidas</h5>
    <div class="row g-3">
        <div class="col-auto">
            <a href="{{ route('admin.muebles.create') }}" class="btn btn-dark">
                <i class="fas fa-plus me-2"></i>Nuevo producto
            </a>
        </div>
        <div class="col-auto">
            <a href="{{ route('admin.usuarios.create') }}" class="btn btn-outline-dark">
                <i class="fas fa-user-plus me-2"></i>Nuevo usuario
            </a>
        </div>
        <div class="col-auto">
            <a href="{{ route('admin.muebles.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-list me-2"></i>Lista de productos
            </a>
        </div>
        <div class="col-auto">
            <a href="{{ route('admin.usuarios.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-users me-2"></i>Lista de usuarios
            </a>
        </div>
    </div>

</div>
@endsection
