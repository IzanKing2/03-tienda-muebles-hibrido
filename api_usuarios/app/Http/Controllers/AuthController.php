<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    private function getAbilitiesForRole($roleName)
    {
        switch ($roleName) {
            case 'Administrador':
                return [
                    'perfil.ver', 'usuarios.ver', 'usuarios.crear', 'usuarios.editar', 'usuarios.eliminar',
                    'muebles.ver', 'muebles.crear', 'muebles.editar', 'muebles.eliminar', 'admin.panel'
                ];
            case 'Gestor':
                return [
                    'perfil.ver', 'muebles.ver', 'muebles.crear', 'muebles.editar', 'muebles.eliminar'
                ];
            case 'Cliente':
            default:
                return [
                    'perfil.ver', 'muebles.ver', 'carrito.gestionar', 'pedidos.crear'
                ];
        }
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:100',
            'apellidos' => 'required|string|max:150',
            'email' => 'required|string|email|max:150|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['errores' => $validator->errors()], 422);
        }

        $clienteRole = Role::where('nombre', 'Cliente')->first();

        $user = User::create([
            'rol_id' => $clienteRole->id,
            'nombre' => $request->nombre,
            'apellidos' => $request->apellidos,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $abilities = $this->getAbilitiesForRole('Cliente');
        $token = $user->createToken('auth_token', $abilities)->plainTextToken;

        return response()->json([
            'mensaje' => 'Usuario registrado exitosamente',
            'usuario' => $user,
            'token' => $token,
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'mensaje' => 'Credenciales incorrectas'
            ], 401);
        }

        $abilities = $this->getAbilitiesForRole($user->rol->nombre);
        $token = $user->createToken('auth_token', $abilities)->plainTextToken;

        return response()->json([
            'mensaje' => 'Login exitoso',
            'usuario' => $user,
            'token' => $token,
            'abilities' => $abilities
        ]);
    }

    public function perfil(Request $request)
    {
        return response()->json([
            'usuario' => $request->user()->load('rol')
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'mensaje' => 'Sesión cerrada correctamente'
        ]);
    }
}
