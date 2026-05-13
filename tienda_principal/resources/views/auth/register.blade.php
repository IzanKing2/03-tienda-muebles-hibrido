<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro – Tienda de Muebles</title>
</head>
<body>
    <h1>Crear cuenta</h1>

    @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form method="POST" action="{{ route('register.post') }}">
        @csrf

        <label for="nombre">Nombre</label>
        <input type="text" id="nombre" name="nombre" value="{{ old('nombre') }}" required>

        <label for="apellidos">Apellidos</label>
        <input type="text" id="apellidos" name="apellidos" value="{{ old('apellidos') }}" required>

        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}" required>

        <label for="password">Contraseña</label>
        <input type="password" id="password" name="password" required>

        <label for="password_confirmation">Confirmar contraseña</label>
        <input type="password" id="password_confirmation" name="password_confirmation" required>

        <button type="submit">Registrarse</button>
    </form>

    <p>¿Ya tienes cuenta? <a href="{{ route('login') }}">Inicia sesión</a></p>
</body>
</html>
