@extends('layouts.app')

@section('title', 'Gestión de Usuarios')

@section('head')
<style>
    .admin-table th {
        font-size: .72rem; text-transform: uppercase; letter-spacing: .5px;
        font-weight: 600; color: #fff; background: var(--color-primary); padding: .85rem 1rem;
    }
    .admin-table td { padding: .75rem 1rem; vertical-align: middle; font-size: .85rem; }
    .admin-table tbody tr { border-bottom: 1px solid var(--color-border); transition: var(--transition); }
    .admin-table tbody tr:hover { background: var(--color-surface); }
    .admin-table tbody tr.row-hl { background: rgba(201,169,110,.04); }
    .act-btn {
        width: 32px; height: 32px;
        display: inline-flex; align-items: center; justify-content: center;
        border-radius: var(--radius-sm); border: 1.5px solid var(--color-border);
        background: transparent; transition: var(--transition);
        font-size: .75rem; color: var(--color-text-muted);
    }
    .act-btn:hover { border-color: var(--color-primary); color: var(--color-primary); }
    .act-btn.danger:hover { border-color: var(--color-danger); color: var(--color-danger); }
    .act-btn.primary:hover { border-color: #3498db; color: #3498db; }
</style>
@endsection

@section('content')
<div class="container py-5 animate-in">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <a href="{{ route('admin.dashboard') }}" style="font-size:.82rem;color:var(--color-text-muted);text-decoration:none;">
                <i class="fas fa-arrow-left me-1"></i>Dashboard
            </a>
            <h1 class="fw-700 ls-tight mt-1 mb-0" style="font-size:1.5rem;">
                <i class="fas fa-users me-2" style="color:var(--color-accent);"></i>Gestión de Usuarios
            </h1>
        </div>
        <a href="{{ route('admin.usuarios.create') }}" class="btn btn-elegant-dark btn-sm">
            <i class="fas fa-user-plus me-2"></i>Nuevo usuario
        </a>
    </div>

    @if(count($usuarios) > 0)
        <div style="background:var(--color-card);border:1px solid var(--color-border);border-radius:var(--radius-lg);overflow:hidden;">
            <div class="table-responsive">
                <table class="table align-middle mb-0 admin-table">
                    <thead>
                        <tr>
                            <th style="width:50px;">ID</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th class="text-center">Rol</th>
                            <th class="text-center">Estado</th>
                            <th class="text-center">Registro</th>
                            <th class="text-end" style="width:110px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($usuarios as $u)
                            @php $esYo = ($u['id'] ?? null) == (session('auth_user')['id'] ?? null); @endphp
                            <tr class="{{ $esYo ? 'row-hl' : '' }}">
                                <td style="color:var(--color-text-muted);font-size:.78rem;">{{ $u['id'] }}</td>
                                <td>
                                    <div class="fw-600">
                                        {{ $u['nombre'] }} {{ $u['apellidos'] ?? '' }}
                                        @if($esYo)
                                            <span style="font-size:.62rem;font-weight:700;padding:.12rem .4rem;border-radius:4px;background:rgba(201,169,110,.12);color:var(--color-accent);">Tú</span>
                                        @endif
                                    </div>
                                </td>
                                <td style="font-size:.82rem;color:var(--color-text-muted);">{{ $u['email'] }}</td>
                                <td class="text-center">
                                    @php
                                        $rol = $u['rol']['nombre'] ?? 'Cliente';
                                        $rs = match($rol) {
                                            'Administrador' => 'background:rgba(192,57,43,.08);color:var(--color-danger);',
                                            'Gestor' => 'background:rgba(201,169,110,.12);color:var(--color-accent);',
                                            default => 'background:var(--color-surface);color:var(--color-text-muted);',
                                        };
                                    @endphp
                                    <span style="font-size:.68rem;font-weight:600;padding:.2rem .55rem;border-radius:50px;{{ $rs }}">{{ $rol }}</span>
                                </td>
                                <td class="text-center">
                                    @if($u['bloqueado_hasta'] ?? false)
                                        <span style="font-size:.68rem;font-weight:600;padding:.2rem .5rem;border-radius:50px;background:rgba(192,57,43,.08);color:var(--color-danger);">Bloqueado</span>
                                    @else
                                        <span style="font-size:.68rem;font-weight:600;padding:.2rem .5rem;border-radius:50px;background:rgba(45,138,78,.08);color:var(--color-success);">Activo</span>
                                    @endif
                                </td>
                                <td class="text-center" style="font-size:.78rem;color:var(--color-text-muted);">
                                    {{ isset($u['created_at']) ? \Carbon\Carbon::parse($u['created_at'])->format('d/m/Y') : '—' }}
                                </td>
                                <td class="text-end">
                                    <div class="d-flex gap-1 justify-content-end">
                                        <a href="{{ route('admin.usuarios.edit', $u['id']) }}" class="act-btn primary" title="Editar"><i class="fas fa-edit"></i></a>
                                        @if(!$esYo)
                                            <form action="{{ route('admin.usuarios.destroy', $u['id']) }}" method="POST"
                                                  onsubmit="return confirm('¿Eliminar al usuario {{ addslashes($u['nombre']) }}?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="act-btn danger" title="Eliminar"><i class="fas fa-trash"></i></button>
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
        <p style="font-size:.78rem;color:var(--color-text-muted);margin-top:.5rem;">Total: {{ count($usuarios) }} usuario(s)</p>
    @else
        <div class="text-center py-5">
            <div style="width:80px;height:80px;border-radius:50%;background:var(--color-surface);display:inline-flex;align-items:center;justify-content:center;margin-bottom:1rem;">
                <i class="fas fa-users fa-2x" style="color:var(--color-border);"></i>
            </div>
            <h5 style="color:var(--color-text-muted);font-weight:600;">No hay usuarios</h5>
        </div>
    @endif
</div>
@endsection
