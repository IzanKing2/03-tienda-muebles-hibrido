<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Resources\UsuarioResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use OpenApi\Attributes as OA;

class UserController extends Controller
{
    #[OA\Get(
        path: '/api/usuarios',
        summary: 'Listar todos los usuarios',
        tags: ['Usuarios'],
        security: [['sanctum' => []]],
        responses: [
            new OA\Response(response: 200, description: 'Lista de usuarios'),
            new OA\Response(response: 403, description: 'No autorizado'),
        ]
    )]
    public function index(Request $request)
    {
        if (!$request->user()->tokenCan('usuarios.ver')) {
            return response()->json(['mensaje' => 'No autorizado'], 403);
        }

        return UsuarioResource::collection(User::with('rol')->get());
    }

    #[OA\Post(
        path: '/api/usuarios',
        summary: 'Crear un nuevo usuario',
        tags: ['Usuarios'],
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(
            required: ['rol_id', 'nombre', 'apellidos', 'email', 'password'],
            properties: [
                new OA\Property(property: 'rol_id',    type: 'integer', example: 3),
                new OA\Property(property: 'nombre',    type: 'string',  example: 'Ana'),
                new OA\Property(property: 'apellidos', type: 'string',  example: 'Martínez'),
                new OA\Property(property: 'email',     type: 'string',  format: 'email'),
                new OA\Property(property: 'password',  type: 'string',  format: 'password'),
            ]
        )),
        responses: [
            new OA\Response(response: 201, description: 'Usuario creado'),
            new OA\Response(response: 403, description: 'No autorizado'),
            new OA\Response(response: 422, description: 'Errores de validación'),
        ]
    )]
    public function store(Request $request)
    {
        if (!$request->user()->tokenCan('usuarios.crear')) {
            return response()->json(['mensaje' => 'No autorizado'], 403);
        }

        $validator = Validator::make($request->all(), [
            'rol_id'    => 'required|exists:roles,id',
            'nombre'    => 'required|string|max:100',
            'apellidos' => 'required|string|max:150',
            'email'     => 'required|string|email|max:150|unique:users',
            'password'  => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['errores' => $validator->errors()], 422);
        }

        $user = User::create([
            'rol_id'    => $request->rol_id,
            'nombre'    => $request->nombre,
            'apellidos' => $request->apellidos,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
        ]);

        return (new UsuarioResource($user->load('rol')))->response()->setStatusCode(201);
    }

    #[OA\Get(
        path: '/api/usuarios/{id}',
        summary: 'Obtener un usuario por ID',
        tags: ['Usuarios'],
        security: [['sanctum' => []]],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
        responses: [
            new OA\Response(response: 200, description: 'Datos del usuario'),
            new OA\Response(response: 403, description: 'No autorizado'),
            new OA\Response(response: 404, description: 'Usuario no encontrado'),
        ]
    )]
    public function show(Request $request, int $id)
    {
        if (!$request->user()->tokenCan('usuarios.ver')) {
            return response()->json(['mensaje' => 'No autorizado'], 403);
        }

        $user = User::with('rol')->find($id);
        if (!$user) return response()->json(['mensaje' => 'Usuario no encontrado'], 404);

        return new UsuarioResource($user);
    }

    #[OA\Put(
        path: '/api/usuarios/{id}',
        summary: 'Actualizar un usuario',
        tags: ['Usuarios'],
        security: [['sanctum' => []]],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
        responses: [
            new OA\Response(response: 200, description: 'Usuario actualizado'),
            new OA\Response(response: 403, description: 'No autorizado'),
            new OA\Response(response: 404, description: 'Usuario no encontrado'),
        ]
    )]
    public function update(Request $request, int $id)
    {
        if (!$request->user()->tokenCan('usuarios.editar')) {
            return response()->json(['mensaje' => 'No autorizado'], 403);
        }

        $user = User::find($id);
        if (!$user) return response()->json(['mensaje' => 'Usuario no encontrado'], 404);

        $validator = Validator::make($request->all(), [
            'rol_id'    => 'sometimes|exists:roles,id',
            'nombre'    => 'sometimes|string|max:100',
            'apellidos' => 'sometimes|string|max:150',
            'email'     => 'sometimes|string|email|max:150|unique:users,email,' . $id,
            'password'  => 'sometimes|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['errores' => $validator->errors()], 422);
        }

        $data = $request->all();
        if ($request->has('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return new UsuarioResource($user->load('rol'));
    }

    #[OA\Delete(
        path: '/api/usuarios/{id}',
        summary: 'Eliminar un usuario',
        tags: ['Usuarios'],
        security: [['sanctum' => []]],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
        responses: [
            new OA\Response(response: 200, description: 'Usuario eliminado'),
            new OA\Response(response: 403, description: 'No autorizado'),
            new OA\Response(response: 404, description: 'Usuario no encontrado'),
            new OA\Response(response: 422, description: 'No puedes eliminarte a ti mismo'),
        ]
    )]
    public function destroy(Request $request, int $id)
    {
        if (!$request->user()->tokenCan('usuarios.eliminar')) {
            return response()->json(['mensaje' => 'No autorizado'], 403);
        }

        $user = User::find($id);
        if (!$user) return response()->json(['mensaje' => 'Usuario no encontrado'], 404);

        if ($user->id === $request->user()->id) {
            return response()->json(['mensaje' => 'No puedes eliminarte a ti mismo'], 422);
        }

        $user->delete();

        return response()->json(['mensaje' => 'Usuario eliminado exitosamente']);
    }
}
