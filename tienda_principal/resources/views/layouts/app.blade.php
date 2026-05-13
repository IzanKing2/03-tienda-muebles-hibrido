<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Tienda de Muebles')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body { background-color: #f8f5f0; }
        .navbar-brand { font-weight: 700; letter-spacing: 1px; }
        .card-producto { transition: transform .2s, box-shadow .2s; }
        .card-producto:hover { transform: translateY(-4px); box-shadow: 0 8px 24px rgba(0,0,0,.12) !important; }
        .hero { background: linear-gradient(135deg, #2c2c2c 0%, #5c4033 100%); }
        .badge-rol { font-size: .75rem; }
        footer { border-top: 1px solid #dee2e6; }
        .cart-badge { font-size: .65rem; top: 2px; right: -4px; }
    </style>
    @yield('head')
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            <i class="fas fa-couch me-2"></i>MueblesHíbrido
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMain">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">Inicio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('muebles*') && !request()->is('admin*') ? 'active' : '' }}"
                       href="{{ route('muebles.index') }}">Catálogo</a>
                </li>
                @if(session('auth_token') && in_array('admin.panel', session('auth_abilities', [])))
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle {{ request()->is('admin*') ? 'active' : '' }}"
                           href="#" data-bs-toggle="dropdown">
                            <i class="fas fa-tools me-1"></i>Admin
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark">
                            <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="{{ route('admin.muebles.index') }}">
                                <i class="fas fa-couch me-2"></i>Gestión de Muebles
                            </a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.usuarios.index') }}">
                                <i class="fas fa-users me-2"></i>Gestión de Usuarios
                            </a></li>
                        </ul>
                    </li>
                @elseif(session('auth_token') && in_array('muebles.crear', session('auth_abilities', [])))
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('admin/muebles*') ? 'active' : '' }}"
                           href="{{ route('admin.muebles.index') }}">
                            <i class="fas fa-tools me-1"></i>Gestión
                        </a>
                    </li>
                @endif
            </ul>
            <ul class="navbar-nav ms-auto align-items-center gap-2">
                @if(session('auth_token'))
                    {{-- Carrito --}}
                    @php
                        $carritoCount = 0;
                        if (session('auth_user')) {
                            $carritoCount = \App\Models\Carrito::where('usuario_id', session('auth_user')['id'])
                                ->withCount('items')
                                ->first()?->items_count ?? 0;
                        }
                    @endphp
                    <li class="nav-item">
                        <a class="nav-link position-relative {{ request()->is('carrito*') ? 'active' : '' }}"
                           href="{{ route('carrito.index') }}" title="Carrito">
                            <i class="fas fa-shopping-cart"></i>
                            @if($carritoCount > 0)
                                <span class="position-absolute badge rounded-pill bg-warning text-dark cart-badge">
                                    {{ $carritoCount }}
                                </span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('pedidos*') ? 'active' : '' }}"
                           href="{{ route('pedidos.index') }}" title="Mis pedidos">
                            <i class="fas fa-box me-1"></i>
                            <span class="d-none d-lg-inline">Pedidos</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('profile') }}">
                            <i class="fas fa-user-circle me-1"></i>
                            {{ session('auth_user')['nombre'] ?? 'Mi perfil' }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-light btn-sm">
                                <i class="fas fa-sign-out-alt me-1"></i>Salir
                            </button>
                        </form>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Iniciar sesión</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-warning btn-sm" href="{{ route('register') }}">Registrarse</a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>

<main>
    @if(session('success'))
        <div class="container mt-3">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="container mt-3">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif

    @yield('content')
</main>

<footer class="py-4 mt-5 text-center text-muted small">
    <div class="container">
        <p class="mb-0">&copy; {{ date('Y') }} MueblesHíbrido &mdash; Tienda de Muebles</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@yield('scripts')
</body>
</html>
