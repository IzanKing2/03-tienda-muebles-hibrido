<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->user()->tokenCan('usuarios.ver')) {
            return response()->json(['mensaje' => 'No autorizado'], 403);
        }

        return response()->json(User::with('rol')->get());
    }

    public function store(Request $request)
    {
        if (!$request->user()->tokenCan('usuarios.crear')) {
            return response()->json(['mensaje' => 'No autorizado'], 403);
        }

        $validator = Validator::make($request->all(), [
            'rol_id' => 'required|exists:roles,id',
            'nombre' => 'required|string|max:100',
            'apellidos' => 'required|string|max:150',
            'email' => 'required|string|email|max:150|unique:users',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['errores' => $validator->errors()], 422);
        }

        $user = User::create([
            'rol_id' => $request->rol_id,
            'nombre' => $request->nombre,
            'apellidos' => $request->apellidos,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json($user->load('rol'), 201);
    }

    public function show(Request $request, $id)
    {
        if (!$request->user()->tokenCan('usuarios.ver')) {
            return response()->json(['mensaje' => 'No autorizado'], 403);
        }

        $user = User::with('rol')->find($id);
        if (!$user) return response()->json(['mensaje' => 'Usuario no encontrado'], 404);

        return response()->json($user);
    }

    public function update(Request $request, $id)
    {
        if (!$request->user()->tokenCan('usuarios.editar')) {
            return response()->json(['mensaje' => 'No autorizado'], 403);
        }

        $user = User::find($id);
        if (!$user) return response()->json(['mensaje' => 'Usuario no encontrado'], 404);

        $validator = Validator::make($request->all(), [
            'rol_id' => 'sometimes|exists:roles,id',
            'nombre' => 'sometimes|string|max:100',
            'apellidos' => 'sometimes|string|max:150',
            'email' => 'sometimes|string|email|max:150|unique:users,email,' . $id,
            'password' => 'sometimes|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['errores' => $validator->errors()], 422);
        }

        $data = $request->all();
        if ($request->has('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return response()->json($user->load('rol'));
    }

    public function destroy(Request $request, $id)
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
