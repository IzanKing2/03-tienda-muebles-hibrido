<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use OpenApi\Attributes as OA;

#[OA\Info(version: '1.0.0', title: 'API Usuarios', description: 'API REST para gestión de usuarios y autenticación')]
#[OA\SecurityScheme(securityScheme: 'sanctum', type: 'http', scheme: 'bearer', bearerFormat: 'JWT')]
class AuthController extends Controller
{
    private function getAbilitiesForRole(string $roleName): array
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

    #[OA\Post(
        path: '/api/register',
        summary: 'Registrar un nuevo usuario',
        tags: ['Autenticación'],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(
            required: ['nombre', 'apellidos', 'email', 'password', 'password_confirmation'],
            properties: [
                new OA\Property(property: 'nombre',                type: 'string',  example: 'Juan'),
                new OA\Property(property: 'apellidos',             type: 'string',  example: 'García López'),
                new OA\Property(property: 'email',                 type: 'string',  format: 'email'),
                new OA\Property(property: 'password',              type: 'string',  format: 'password'),
                new OA\Property(property: 'password_confirmation', type: 'string',  format: 'password'),
            ]
        )),
        responses: [
            new OA\Response(response: 201, description: 'Usuario registrado con token'),
            new OA\Response(response: 422, description: 'Errores de validación'),
        ]
    )]
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre'    => 'required|string|max:100',
            'apellidos' => 'required|string|max:150',
            'email'     => 'required|string|email|max:150|unique:users',
            'password'  => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['errores' => $validator->errors()], 422);
        }

        $clienteRole = Role::where('nombre', 'Cliente')->first();

        if (!$clienteRole) {
            return response()->json(['mensaje' => 'Error de configuración del servidor: rol Cliente no encontrado.'], 500);
        }

        $user = User::create([
            'rol_id'    => $clienteRole->id,
            'nombre'    => $request->nombre,
            'apellidos' => $request->apellidos,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
        ]);

        $abilities = $this->getAbilitiesForRole('Cliente');
        $token = $user->createToken('auth_token', $abilities)->plainTextToken;

        return response()->json([
            'mensaje' => 'Usuario registrado exitosamente',
            'usuario' => $user,
            'token'   => $token,
        ], 201);
    }

    #[OA\Post(
        path: '/api/login',
        summary: 'Iniciar sesión',
        tags: ['Autenticación'],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(
            required: ['email', 'password'],
            properties: [
                new OA\Property(property: 'email',    type: 'string', format: 'email', example: 'admin@tienda.com'),
                new OA\Property(property: 'password', type: 'string', format: 'password'),
            ]
        )),
        responses: [
            new OA\Response(response: 200, description: 'Login exitoso con token y abilities'),
            new OA\Response(response: 401, description: 'Credenciales incorrectas'),
        ]
    )]
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['mensaje' => 'Credenciales incorrectas'], 401);
        }

        $abilities = $this->getAbilitiesForRole($user->rol->nombre);
        $token = $user->createToken('auth_token', $abilities)->plainTextToken;

        return response()->json([
            'mensaje'   => 'Login exitoso',
            'usuario'   => $user,
            'token'     => $token,
            'abilities' => $abilities,
        ]);
    }

    #[OA\Get(
        path: '/api/perfil',
        summary: 'Obtener perfil del usuario autenticado',
        tags: ['Autenticación'],
        security: [['sanctum' => []]],
        responses: [
            new OA\Response(response: 200, description: 'Datos del perfil'),
            new OA\Response(response: 401, description: 'No autenticado'),
        ]
    )]
    public function perfil(Request $request)
    {
        return response()->json([
            'usuario' => $request->user()->load('rol')
        ]);
    }

    #[OA\Post(
        path: '/api/logout',
        summary: 'Cerrar sesión',
        tags: ['Autenticación'],
        security: [['sanctum' => []]],
        responses: [new OA\Response(response: 200, description: 'Sesión cerrada')]
    )]
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['mensaje' => 'Sesión cerrada correctamente']);
    }
}
