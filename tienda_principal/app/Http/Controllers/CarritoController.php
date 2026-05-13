<?php

namespace App\Http\Controllers;

use App\Models\Carrito;
use App\Models\CarritoItem;
use App\Services\MueblesService;
use Illuminate\Http\Request;

class CarritoController extends Controller
{
    public function __construct(protected MueblesService $mueblesService) {}

    // ─── Helper: obtener o crear el carrito del usuario actual ───────────────
    private function carritoUsuario(): Carrito
    {
        $usuarioId = session('auth_user')['id'];
        return Carrito::firstOrCreate(['usuario_id' => $usuarioId]);
    }

    // ─── Listado del carrito ──────────────────────────────────────────────────
    public function index()
    {
        if (!session('auth_token')) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para ver tu carrito.');
        }

        $carrito = $this->carritoUsuario()->load('items');
        $total   = $carrito->total();

        return view('carrito.index', compact('carrito', 'total'));
    }

    // ─── Añadir producto al carrito ───────────────────────────────────────────
    public function agregar(Request $request)
    {
        if (!session('auth_token')) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para añadir productos al carrito.');
        }

        $request->validate([
            'producto_id' => 'required|integer',
            'cantidad'    => 'required|integer|min:1|max:99',
        ]);

        $productoId = (int) $request->input('producto_id');
        $respuesta  = $this->mueblesService->getMuebleById($productoId);

        if (!$respuesta) {
            return back()->with('error', 'Producto no encontrado.');
        }

        // La API puede devolver el objeto directamente o bajo clave 'data'
        $producto = $respuesta['data'] ?? $respuesta;

        if (!isset($producto['id'])) {
            return back()->with('error', 'Producto no encontrado.');
        }

        if (($producto['stock'] ?? 0) <= 0) {
            return back()->with('error', 'Este producto está agotado.');
        }

        // Imagen principal (snapshot)
        $imagen   = null;
        $imagenes = $producto['galeria']['imagenes'] ?? [];
        foreach ($imagenes as $img) {
            if ($img['es_principal']) { $imagen = $img['ruta']; break; }
        }
        if (!$imagen && count($imagenes)) {
            $imagen = $imagenes[0]['ruta'] ?? null;
        }

        $carrito  = $this->carritoUsuario();
        $cantidad = (int) $request->input('cantidad', 1);

        $item = $carrito->items()->where('producto_id', $productoId)->first();

        if ($item) {
            $nueva = $item->cantidad + $cantidad;
            $item->update(['cantidad' => min($nueva, $producto['stock'])]);
        } else {
            $carrito->items()->create([
                'producto_id' => $producto['id'],
                'nombre'      => $producto['nombre'],
                'precio'      => (float) $producto['precio'],
                'cantidad'    => min($cantidad, $producto['stock']),
                'imagen'      => $imagen,
            ]);
        }

        return redirect()->route('carrito.index')
            ->with('success', '«' . $producto['nombre'] . '» añadido al carrito.');
    }

    // ─── Actualizar cantidad de un item ───────────────────────────────────────
    public function actualizar(Request $request, int $itemId)
    {
        $request->validate(['cantidad' => 'required|integer|min:1|max:99']);

        $item = CarritoItem::whereHas('carrito', fn($q) => $q->where('usuario_id', session('auth_user')['id']))
            ->findOrFail($itemId);

        $item->update(['cantidad' => (int) $request->input('cantidad')]);

        return redirect()->route('carrito.index')->with('success', 'Cantidad actualizada.');
    }

    // ─── Eliminar un item del carrito ─────────────────────────────────────────
    public function eliminar(int $itemId)
    {
        $item = CarritoItem::whereHas('carrito', fn($q) => $q->where('usuario_id', session('auth_user')['id']))
            ->findOrFail($itemId);

        $item->delete();

        return redirect()->route('carrito.index')->with('success', 'Producto eliminado del carrito.');
    }

    // ─── Vaciar todo el carrito ───────────────────────────────────────────────
    public function vaciar()
    {
        $this->carritoUsuario()->items()->delete();

        return redirect()->route('carrito.index')->with('success', 'Carrito vaciado.');
    }
}
