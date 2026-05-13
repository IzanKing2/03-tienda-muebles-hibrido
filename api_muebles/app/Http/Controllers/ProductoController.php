<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Http\Resources\ProductoResource;
use App\Http\Resources\ProductoCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductoController extends Controller
{
    public function index(Request $request)
    {
        $query = Producto::with('categorias', 'galeria.imagenes');
        
        // Filter by category
        if ($request->has('categoria_id')) {
            $query->whereHas('categorias', function($q) use ($request) {
                $q->where('categorias.id', $request->categoria_id);
            });
        }

        // Filter by price range
        if ($request->has('precio_min')) {
            $query->where('precio', '>=', $request->precio_min);
        }
        if ($request->has('precio_max')) {
            $query->where('precio', '<=', $request->precio_max);
        }

        // Filter by exact color
        if ($request->has('color')) {
            $query->where('color_principal', $request->color);
        }

        // Filter by materials (contains)
        if ($request->has('materiales')) {
            $query->where('materiales', 'like', '%' . $request->materiales . '%');
        }

        // Filter by destacado
        if ($request->has('destacado')) {
            $query->where('destacado', (bool) $request->destacado);
        }

        // Text Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nombre', 'like', '%' . $search . '%')
                  ->orWhere('descripcion', 'like', '%' . $search . '%');
            });
        }

        // Sorting
        $sortField = $request->get('sort', 'id');
        $sortOrder = $request->get('order', 'asc');
        $allowedSorts = ['id', 'precio', 'nombre', 'created_at', 'destacado'];

        if (in_array($sortField, $allowedSorts)) {
            $query->orderBy($sortField, $sortOrder === 'desc' ? 'desc' : 'asc');
        }

        // Pagination
        $perPage = $request->get('per_page', 12);
        return new ProductoCollection($query->paginate($perPage));
    }

    public function show($id)
    {
        $producto = Producto::with('categorias', 'galeria.imagenes')->find($id);
        if (!$producto) return response()->json(['mensaje' => 'No encontrado'], 404);
        return new ProductoResource($producto);
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

        return (new ProductoResource($producto->load('categorias', 'galeria.imagenes')))->response()->setStatusCode(201);
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

        return new ProductoResource($producto->load('categorias', 'galeria.imagenes'));
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
