@extends('layouts.app')

@section('title', 'Gestión de Usuarios')

@section('content')
<div class="container py-5">

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <a href="{{ route('admin.dashboard') }}" class="text-muted text-decoration-none small">
                <i class="fas fa-arrow-left me-1"></i>Dashboard
            </a>
            <h1 class="h3 fw-bold mt-1"><i class="fas fa-users me-2"></i>Gestión de Usuarios</h1>
        </div>
        <a href="{{ route('admin.usuarios.create') }}" class="btn btn-dark">
            <i class="fas fa-user-plus me-2"></i>Nuevo usuario
        </a>
    </div>

    @if(count($usuarios) > 0)
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th style="width:50px;">ID</th>
                                <th>Nombre</th>
                                <th>Email</th>
                                <th class="text-center">Rol</th>
                                <th class="text-center">Estado</th>
                                <th class="text-center">Registro</th>
                                <th class="text-end" style="width:130px;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($usuarios as $u)
                                @php $esYo = ($u['id'] ?? null) == (session('auth_user')['id'] ?? null); @endphp
                                <tr class="{{ $esYo ? 'table-active' : '' }}">
                                    <td class="text-muted small">{{ $u['id'] }}</td>
                                    <td>
                                        <div class="fw-semibold">
                                            {{ $u['nombre'] }} {{ $u['apellidos'] ?? '' }}
                                            @if($esYo)
                                                <span class="badge bg-warning text-dark ms-1 small">Tú</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="small text-muted">{{ $u['email'] }}</td>
                                    <td class="text-center">
                                        @php
                                            $rol = $u['rol']['nombre'] ?? 'Cliente';
                                            $badgeColor = match($rol) {
                                                'Administrador' => 'bg-danger',
                                                'Gestor'        => 'bg-warning text-dark',
                                                default         => 'bg-secondary',
                                            };
                                        @endphp
                                        <span class="badge {{ $badgeColor }}">{{ $rol }}</span>
                                    </td>
                                    <td class="text-center">
                                        @if($u['bloqueado_hasta'] ?? false)
                                            <span class="badge bg-danger">Bloqueado</span>
                                        @else
                                            <span class="badge bg-success">Activo</span>
                                        @endif
                                    </td>
                                    <td class="text-center small text-muted">
                                        {{ isset($u['created_at']) ? \Carbon\Carbon::parse($u['created_at'])->format('d/m/Y') : '—' }}
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex gap-1 justify-content-end">
                                            <a href="{{ route('admin.usuarios.edit', $u['id']) }}"
                                               class="btn btn-sm btn-outline-primary" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @if(!$esYo)
                                                <form action="{{ route('admin.usuarios.destroy', $u['id']) }}"
                                                      method="POST"
                                                      onsubmit="return confirm('¿Eliminar al usuario {{ addslashes($u['nombre']) }}?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <p class="text-muted small mt-2">Total: {{ count($usuarios) }} usuario(s)</p>
    @else
        <div class="text-center py-5">
            <i class="fas fa-users fa-4x text-secondary opacity-50 mb-3"></i>
            <h5 class="text-muted">No hay usuarios registrados</h5>
        </div>
    @endif

</div>
@endsection
