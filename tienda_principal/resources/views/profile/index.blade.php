@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-header bg-dark text-white p-4">
                    <h3 class="mb-0"><i class="fas fa-user-circle me-2"></i>Mi Perfil</h3>
                </div>
                <div class="card-body p-5">
                    <div class="row align-items-center mb-4">
                        <div class="col-auto">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 80px; height: 80px; font-size: 2rem;">
                                {{ strtoupper(substr($usuario['nombre'], 0, 1)) }}
                            </div>
                        </div>
                        <div class="col">
                            <h2 class="h4 mb-0">{{ $usuario['nombre'] }} {{ $usuario['apellidos'] }}</h2>
                            <span class="badge bg-info text-dark">{{ $usuario['rol']['nombre'] }}</span>
                        </div>
                    </div>

                    <hr>

                    <div class="row mt-4">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small text-uppercase fw-bold">Correo Electrónico</label>
                            <p class="fs-5">{{ $usuario['email'] }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small text-uppercase fw-bold">Miembro desde</label>
                            <p class="fs-5">{{ date('d/m/Y', strtotime($usuario['created_at'])) }}</p>
                        </div>
                    </div>

                    <div class="mt-4 d-flex gap-2">
                        <a href="#" class="btn btn-primary px-4">Editar Perfil</a>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger px-4">Cerrar Sesión</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
