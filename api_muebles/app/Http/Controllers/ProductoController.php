<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductoController extends Controller
{
    public function index(Request $request)
    {
        $query = Producto::with('categorias', 'galeria.imagenes');
        
        if ($request->has('categoria_id')) {
            $query->whereHas('categorias', function($q) use ($request) {
                $q->where('categorias.id', $request->categoria_id);
            });
        }

        return response()->json($query->get());
    }

    public function show($id)
    {
        $producto = Producto::with('categorias', 'galeria.imagenes')->find($id);
        if (!$producto) return response()->json(['mensaje' => 'No encontrado'], 404);
        return response()->json($producto);
    }

    public function store(Request $request)
    {
        if (!$request->user()->tokenCan('muebles.crear')) {
            return response()->json(['mensaje' => 'No autorizado'], 403);
        }

        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:200',
            'descripcion' => 'required|string',
            'precio' => 'required|numeric',
            'stock' => 'required|integer',
            'materiales' => 'required|string',
            'dimensiones' => 'required|string|max:100',
            'color_principal' => 'required|string|max:50',
            'categorias' => 'array'
        ]);

        if ($validator->fails()) return response()->json(['errores' => $validator->errors()], 422);

        $producto = Producto::create($request->all());

        if ($request->has('categorias')) {
            $producto->categorias()->sync($request->categorias);
        }

        return response()->json($producto->load('categorias'), 201);
    }

    public function update(Request $request, $id)
    {
        if (!$request->user()->tokenCan('muebles.editar')) {
            return response()->json(['mensaje' => 'No autorizado'], 403);
        }

        $producto = Producto::find($id);
        if (!$producto) return response()->json(['mensaje' => 'No encontrado'], 404);

        $producto->update($request->all());

        if ($request->has('categorias')) {
            $producto->categorias()->sync($request->categorias);
        }

        return response()->json($producto->load('categorias'));
    }

    public function destroy(Request $request, $id)
    {
        if (!$request->user()->tokenCan('muebles.eliminar')) {
            return response()->json(['mensaje' => 'No autorizado'], 403);
        }

        $producto = Producto::find($id);
        if (!$producto) return response()->json(['mensaje' => 'No encontrado'], 404);

        $producto->delete();
        return response()->json(['mensaje' => 'Eliminado']);
    }
}
