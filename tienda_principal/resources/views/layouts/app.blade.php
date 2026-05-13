<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Tienda de Muebles')</title>

    {{-- Google Fonts: Inter para una tipografía moderna y limpia --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Bootstrap 5 + FontAwesome --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    {{-- ═══ ESTILOS GLOBALES ELEGANTES ═══ --}}
    <style>
        /* ── Variables CSS: sistema de diseño centralizado ── */
        :root {
            --color-primary: #1a1a2e;       /* Azul muy oscuro, casi negro */
            --color-secondary: #16213e;     /* Azul noche profundo */
            --color-accent: #c9a96e;        /* Dorado cálido elegante */
            --color-accent-hover: #b8943f;  /* Dorado más intenso */
            --color-surface: #fafaf8;       /* Blanco cálido de fondo */
            --color-card: #ffffff;          /* Blanco puro para tarjetas */
            --color-text: #2d2d2d;          /* Gris oscuro para texto */
            --color-text-muted: #8a8a8a;    /* Gris suave */
            --color-border: #e8e6e1;        /* Borde muy sutil */
            --color-success: #2d8a4e;
            --color-danger: #c0392b;
            --shadow-sm: 0 1px 3px rgba(0,0,0,.04), 0 1px 2px rgba(0,0,0,.06);
            --shadow-md: 0 4px 16px rgba(0,0,0,.06), 0 1px 4px rgba(0,0,0,.04);
            --shadow-lg: 0 10px 40px rgba(0,0,0,.08);
            --shadow-hover: 0 14px 44px rgba(0,0,0,.12);
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
            --radius-xl: 20px;
            --transition: all .3s cubic-bezier(.4,0,.2,1);
        }

        /* ── Reset y tipografía base ── */
        * { font-family: 'Inter', system-ui, -apple-system, sans-serif; }
        body {
            background-color: var(--color-surface);
            color: var(--color-text);
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* ── Scrollbar elegante (Chrome/Edge) ── */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #ccc; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #999; }

        /* ══════════════════════════════════════════════════
           NAVBAR MINIMALISTA CON EFECTO GLASSMORPHISM
           ══════════════════════════════════════════════════ */
        .navbar-elegant {
            background: rgba(26, 26, 46, 0.97);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255,255,255,.06);
            padding: .7rem 0;
            transition: var(--transition);
        }
        .navbar-elegant .navbar-brand {
            font-weight: 700;
            letter-spacing: .5px;
            font-size: 1.15rem;
            color: #fff !important;
            display: flex;
            align-items: center;
            gap: .5rem;
        }
        .navbar-elegant .navbar-brand .brand-icon {
            background: linear-gradient(135deg, var(--color-accent), var(--color-accent-hover));
            width: 32px; height: 32px;
            border-radius: var(--radius-sm);
            display: flex; align-items: center; justify-content: center;
            font-size: .85rem;
        }
        .navbar-elegant .nav-link {
            color: rgba(255,255,255,.7) !important;
            font-weight: 500;
            font-size: .875rem;
            padding: .5rem .85rem !important;
            border-radius: var(--radius-sm);
            transition: var(--transition);
            position: relative;
        }
        .navbar-elegant .nav-link:hover,
        .navbar-elegant .nav-link.active {
            color: #fff !important;
            background: rgba(255,255,255,.08);
        }
        .navbar-elegant .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: 2px;
            left: 50%; transform: translateX(-50%);
            width: 16px; height: 2px;
            background: var(--color-accent);
            border-radius: 2px;
        }
        .navbar-elegant .dropdown-menu {
            background: var(--color-primary);
            border: 1px solid rgba(255,255,255,.1);
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-lg);
            padding: .5rem;
            margin-top: .5rem;
        }
        .navbar-elegant .dropdown-item {
            color: rgba(255,255,255,.75);
            border-radius: var(--radius-sm);
            padding: .5rem .75rem;
            font-size: .85rem;
            transition: var(--transition);
        }
        .navbar-elegant .dropdown-item:hover {
            background: rgba(255,255,255,.1);
            color: #fff;
        }

        /* ── Botones elegantes ── */
        .btn-accent {
            background: linear-gradient(135deg, var(--color-accent), var(--color-accent-hover));
            color: var(--color-primary);
            border: none;
            font-weight: 600;
            border-radius: var(--radius-sm);
            transition: var(--transition);
        }
        .btn-accent:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 20px rgba(201,169,110,.35);
            color: var(--color-primary);
        }
        .btn-elegant-dark {
            background: var(--color-primary);
            color: #fff;
            border: none;
            font-weight: 500;
            border-radius: var(--radius-sm);
            transition: var(--transition);
        }
        .btn-elegant-dark:hover {
            background: var(--color-secondary);
            color: #fff;
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }
        .btn-outline-elegant {
            border: 1.5px solid var(--color-border);
            color: var(--color-text);
            background: transparent;
            font-weight: 500;
            border-radius: var(--radius-sm);
            transition: var(--transition);
        }
        .btn-outline-elegant:hover {
            border-color: var(--color-primary);
            color: var(--color-primary);
            background: rgba(26,26,46,.03);
        }

        /* ── Tarjetas elegantes ── */
        .card-elegant {
            background: var(--color-card);
            border: 1px solid var(--color-border);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
            overflow: hidden;
        }
        .card-elegant:hover {
            box-shadow: var(--shadow-hover);
            transform: translateY(-4px);
        }

        /* ── Badge del carrito ── */
        .cart-badge {
            font-size: .6rem;
            top: 0; right: -2px;
            background: var(--color-accent) !important;
            color: var(--color-primary) !important;
            font-weight: 700;
            min-width: 18px; height: 18px;
            display: flex; align-items: center; justify-content: center;
            border-radius: 50%;
        }

        /* ── Alertas refinadas ── */
        .alert {
            border: none;
            border-radius: var(--radius-md);
            font-size: .875rem;
            font-weight: 500;
        }

        /* ══════════════════════════════════════════════════
           FOOTER ELEGANTE Y MINIMALISTA
           ══════════════════════════════════════════════════ */
        .footer-elegant {
            background: var(--color-primary);
            color: rgba(255,255,255,.5);
            padding: 2.5rem 0 1.5rem;
        }
        .footer-elegant .footer-brand {
            color: #fff;
            font-weight: 700;
            font-size: 1.1rem;
            display: flex; align-items: center; gap: .5rem;
        }
        .footer-elegant .footer-links a {
            color: rgba(255,255,255,.5);
            text-decoration: none;
            font-size: .85rem;
            transition: var(--transition);
        }
        .footer-elegant .footer-links a:hover { color: var(--color-accent); }
        .footer-elegant hr { border-color: rgba(255,255,255,.08); margin: 1.5rem 0; }

        /* ── Animación fade-in sutil al cargar ── */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-in {
            animation: fadeInUp .5s ease-out forwards;
        }

        /* ── Utilidades ── */
        .text-accent { color: var(--color-accent) !important; }
        .bg-surface { background: var(--color-surface) !important; }
        .fw-800 { font-weight: 800 !important; }
        .ls-tight { letter-spacing: -.5px; }
        .ls-wide { letter-spacing: .5px; }

        /* ── Links de navegación del navbar en mobile ── */
        .navbar-elegant .navbar-toggler {
            border: 1px solid rgba(255,255,255,.15);
            padding: .4rem .6rem;
        }
        .navbar-elegant .navbar-toggler-icon {
            filter: brightness(0) invert(1);
        }
    </style>
    @yield('head')
</head>
<body>

{{-- ═══ NAVBAR ELEGANTE ═══ --}}
<nav class="navbar navbar-expand-lg navbar-elegant sticky-top">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            <span class="brand-icon"><i class="fas fa-couch"></i></span>
            MueblesHíbrido
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMain">
            <ul class="navbar-nav me-auto ms-3">
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
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                <i class="fas fa-tachometer-alt me-2 opacity-50"></i>Dashboard
                            </a></li>
                            <li><hr class="dropdown-divider" style="border-color:rgba(255,255,255,.08)"></li>
                            <li><a class="dropdown-item" href="{{ route('admin.muebles.index') }}">
                                <i class="fas fa-couch me-2 opacity-50"></i>Gestión de Muebles
                            </a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.usuarios.index') }}">
                                <i class="fas fa-users me-2 opacity-50"></i>Gestión de Usuarios
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
            <ul class="navbar-nav ms-auto align-items-center gap-1">
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
                            <i class="fas fa-shopping-bag"></i>
                            @if($carritoCount > 0)
                                <span class="position-absolute badge rounded-pill cart-badge">
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
                    <li class="nav-item ms-1">
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-light"
                                    style="border-color:rgba(255,255,255,.15);font-size:.8rem;padding:.35rem .75rem;">
                                <i class="fas fa-sign-out-alt me-1"></i>Salir
                            </button>
                        </form>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Iniciar sesión</a>
                    </li>
                    <li class="nav-item ms-1">
                        <a class="btn btn-accent btn-sm px-3" href="{{ route('register') }}">Registrarse</a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>

{{-- ═══ CONTENIDO PRINCIPAL ═══ --}}
<main>
    @if(session('success'))
        <div class="container mt-4 animate-in">
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2" role="alert"
                 style="background:rgba(45,138,78,.08);color:var(--color-success);border-left:4px solid var(--color-success);">
                <i class="fas fa-check-circle"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" style="font-size:.7rem;"></button>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="container mt-4 animate-in">
            <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2" role="alert"
                 style="background:rgba(192,57,43,.06);color:var(--color-danger);border-left:4px solid var(--color-danger);">
                <i class="fas fa-exclamation-circle"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" style="font-size:.7rem;"></button>
            </div>
        </div>
    @endif

    @yield('content')
</main>

{{-- ═══ FOOTER ELEGANTE ═══ --}}
<footer class="footer-elegant mt-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-4 mb-3 mb-md-0">
                <div class="footer-brand mb-2">
                    <span class="brand-icon" style="width:28px;height:28px;font-size:.75rem;border-radius:6px;display:inline-flex;align-items:center;justify-content:center;background:linear-gradient(135deg,var(--color-accent),var(--color-accent-hover));">
                        <i class="fas fa-couch" style="color:var(--color-primary);"></i>
                    </span>
                    MueblesHíbrido
                </div>
                <p class="mb-0" style="font-size:.8rem;">Diseño, calidad y confort en una sola tienda.</p>
            </div>
            <div class="col-md-4 mb-3 mb-md-0">
                <div class="footer-links d-flex gap-3 justify-content-md-center">
                    <a href="{{ url('/') }}">Inicio</a>
                    <a href="{{ route('muebles.index') }}">Catálogo</a>
                    @if(!session('auth_token'))
                        <a href="{{ route('login') }}">Acceder</a>
                    @endif
                </div>
            </div>
            <div class="col-md-4 text-md-end">
                <p class="mb-0" style="font-size:.78rem;">&copy; {{ date('Y') }} MueblesHíbrido</p>
                <p class="mb-0" style="font-size:.72rem;opacity:.5;">Proyecto educativo</p>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@yield('scripts')
</body>
</html>
