<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Http\Resources\ProductoResource;
use App\Http\Resources\ProductoCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use OpenApi\Attributes as OA;

#[OA\Info(version: '1.0.0', title: 'API Muebles', description: 'API REST para gestión del catálogo de muebles')]
#[OA\SecurityScheme(securityScheme: 'sanctum', type: 'http', scheme: 'bearer', bearerFormat: 'JWT')]
class ProductoController extends Controller
{
    #[OA\Get(
        path: '/api/productos',
        summary: 'Listar productos con filtros y paginación',
        tags: ['Productos'],
        parameters: [
            new OA\Parameter(name: 'search',       in: 'query', schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'categoria_id', in: 'query', schema: new OA\Schema(type: 'integer')),
            new OA\Parameter(name: 'precio_min',   in: 'query', schema: new OA\Schema(type: 'number')),
            new OA\Parameter(name: 'precio_max',   in: 'query', schema: new OA\Schema(type: 'number')),
            new OA\Parameter(name: 'destacado',    in: 'query', schema: new OA\Schema(type: 'boolean')),
            new OA\Parameter(name: 'sort',         in: 'query', schema: new OA\Schema(type: 'string', enum: ['id','precio','nombre','created_at','destacado'])),
            new OA\Parameter(name: 'order',        in: 'query', schema: new OA\Schema(type: 'string', enum: ['asc','desc'])),
            new OA\Parameter(name: 'per_page',     in: 'query', schema: new OA\Schema(type: 'integer', default: 12)),
        ],
        responses: [new OA\Response(response: 200, description: 'Lista paginada de productos')]
    )]
    public function index(Request $request)
    {
        $query = Producto::with('categorias', 'galeria.imagenes');

        if ($request->has('categoria_id')) {
            $query->whereHas('categorias', function($q) use ($request) {
                $q->where('categorias.id', $request->categoria_id);
            });
        }

        if ($request->has('precio_min')) {
            $query->where('precio', '>=', $request->precio_min);
        }
        if ($request->has('precio_max')) {
            $query->where('precio', '<=', $request->precio_max);
        }

        if ($request->has('color')) {
            $query->where('color_principal', $request->color);
        }

        if ($request->has('materiales')) {
            $query->where('materiales', 'like', '%' . $request->materiales . '%');
        }

        if ($request->has('destacado')) {
            $query->where('destacado', (bool) $request->destacado);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nombre', 'like', '%' . $search . '%')
                  ->orWhere('descripcion', 'like', '%' . $search . '%');
            });
        }

        $sortField = $request->input('sort', 'id');
        $sortOrder = $request->input('order', 'asc');
        $allowedSorts = ['id', 'precio', 'nombre', 'created_at', 'destacado'];

        if (in_array($sortField, $allowedSorts)) {
            $query->orderBy($sortField, $sortOrder === 'desc' ? 'desc' : 'asc');
        }

        $perPage = $request->input('per_page', 12);
        return new ProductoCollection($query->paginate($perPage));
    }

    #[OA\Get(
        path: '/api/productos/{id}',
        summary: 'Obtener un producto por ID',
        tags: ['Productos'],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
        responses: [
            new OA\Response(response: 200, description: 'Datos del producto'),
            new OA\Response(response: 404, description: 'Producto no encontrado'),
        ]
    )]
    public function show($id)
    {
        $producto = Producto::with('categorias', 'galeria.imagenes')->find($id);
        if (!$producto) return response()->json(['mensaje' => 'No encontrado'], 404);
        return new ProductoResource($producto);
    }

    #[OA\Post(
        path: '/api/productos',
        summary: 'Crear un nuevo producto',
        tags: ['Productos'],
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(
            required: ['nombre','descripcion','precio','stock','materiales','dimensiones','color_principal'],
            properties: [
                new OA\Property(property: 'nombre',          type: 'string'),
                new OA\Property(property: 'descripcion',     type: 'string'),
                new OA\Property(property: 'precio',          type: 'number'),
                new OA\Property(property: 'stock',           type: 'integer'),
                new OA\Property(property: 'materiales',      type: 'string'),
                new OA\Property(property: 'dimensiones',     type: 'string'),
                new OA\Property(property: 'color_principal', type: 'string'),
                new OA\Property(property: 'destacado',       type: 'boolean'),
            ]
        )),
        responses: [
            new OA\Response(response: 201, description: 'Producto creado'),
            new OA\Response(response: 403, description: 'No autorizado'),
            new OA\Response(response: 422, description: 'Errores de validación'),
        ]
    )]
    public function store(Request $request)
    {
        if (!$request->user()->tokenCan('muebles.crear')) {
            return response()->json(['mensaje' => 'No autorizado'], 403);
        }

        $validator = Validator::make($request->all(), [
            'nombre'          => 'required|string|max:200',
            'descripcion'     => 'required|string',
            'precio'          => 'required|numeric',
            'stock'           => 'required|integer',
            'materiales'      => 'required|string',
            'dimensiones'     => 'required|string|max:100',
            'color_principal' => 'required|string|max:50',
            'categorias'      => 'array',
        ]);

        if ($validator->fails()) return response()->json(['errores' => $validator->errors()], 422);

        $producto = Producto::create($request->all());

        if ($request->has('categorias')) {
            $producto->categorias()->sync($request->categorias);
        }

        return (new ProductoResource($producto->load('categorias', 'galeria.imagenes')))->response()->setStatusCode(201);
    }

    #[OA\Put(
        path: '/api/productos/{id}',
        summary: 'Actualizar un producto',
        tags: ['Productos'],
        security: [['sanctum' => []]],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
        responses: [
            new OA\Response(response: 200, description: 'Producto actualizado'),
            new OA\Response(response: 403, description: 'No autorizado'),
            new OA\Response(response: 404, description: 'No encontrado'),
        ]
    )]
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

    #[OA\Delete(
        path: '/api/productos/{id}',
        summary: 'Eliminar un producto',
        tags: ['Productos'],
        security: [['sanctum' => []]],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
        responses: [
            new OA\Response(response: 200, description: 'Producto eliminado'),
            new OA\Response(response: 403, description: 'No autorizado'),
            new OA\Response(response: 404, description: 'No encontrado'),
        ]
    )]
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
