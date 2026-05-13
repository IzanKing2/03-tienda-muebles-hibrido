<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoriaController extends Controller
{
    public function index()
    {
        return response()->json(Categoria::all());
    }

    public function show($id)
    {
        $categoria = Categoria::find($id);
        if (!$categoria) return response()->json(['mensaje' => 'No encontrado'], 404);
        return response()->json($categoria);
    }

    public function store(Request $request)
    {
        if (!$request->user()->tokenCan('muebles.crear')) {
            return response()->json(['mensaje' => 'No autorizado'], 403);
        }

        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string'
        ]);

        if ($validator->fails()) return response()->json(['errores' => $validator->errors()], 422);

        $categoria = Categoria::create($request->all());
        return response()->json($categoria, 201);
    }

    public function update(Request $request, $id)
    {
        if (!$request->user()->tokenCan('muebles.editar')) {
            return response()->json(['mensaje' => 'No autorizado'], 403);
        }

        $categoria = Categoria::find($id);
        if (!$categoria) return response()->json(['mensaje' => 'No encontrado'], 404);

        $categoria->update($request->all());
        return response()->json($categoria);
    }

    public function destroy(Request $request, $id)
    {
        if (!$request->user()->tokenCan('muebles.eliminar')) {
            return response()->json(['mensaje' => 'No autorizado'], 403);
        }

        $categoria = Categoria::find($id);
        if (!$categoria) return response()->json(['mensaje' => 'No encontrado'], 404);

        $categoria->delete();
        return response()->json(['mensaje' => 'Eliminado']);
    }
}
