<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión – Tienda de Muebles</title>
</head>
<body>
    <h1>Iniciar sesión</h1>

    @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form method="POST" action="{{ route('login.post') }}">
        @csrf

        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>

        <label for="password">Contraseña</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">Entrar</button>
    </form>

    <p>¿No tienes cuenta? <a href="{{ route('register') }}">Regístrate</a></p>
</body>
</html>
