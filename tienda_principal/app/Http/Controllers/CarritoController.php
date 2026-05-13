<?php

namespace App\Http\Controllers;

use App\Services\MueblesService;
use Illuminate\Http\Request;

class CarritoController extends Controller
{
    public function __construct(protected MueblesService $mueblesService) {}

    public function index()
    {
        if (!session('auth_token')) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para ver tu carrito.');
        }
        $carrito = session('carrito', []);
        $total   = collect($carrito)->sum(fn($i) => $i['precio'] * $i['cantidad']);
        return view('carrito.index', compact('carrito', 'total'));
    }

    public function agregar(Request $request)
    {
        if (!session('auth_token')) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para añadir productos al carrito.');
        }

        $request->validate([
            'producto_id' => 'required|integer',
            'cantidad'    => 'required|integer|min:1|max:99',
        ]);

        $id      = $request->input('producto_id');
        $mueble  = $this->mueblesService->getMuebleById($id);

        if (!$mueble || !isset($mueble['id'])) {
            return back()->with('error', 'Producto no encontrado.');
        }

        $producto = $mueble['data'] ?? $mueble;

        if (($producto['stock'] ?? 0) <= 0) {
            return back()->with('error', 'Este producto está agotado.');
        }

        // Imagen principal
        $imagen = null;
        $imagenes = $producto['galeria']['imagenes'] ?? [];
        foreach ($imagenes as $img) {
            if ($img['es_principal']) { $imagen = $img['ruta']; break; }
        }
        if (!$imagen && count($imagenes)) {
            $imagen = $imagenes[0]['ruta'] ?? null;
        }

        $carrito  = session('carrito', []);
        $cantidad = (int) $request->input('cantidad', 1);

        if (isset($carrito[$id])) {
            $nueva = $carrito[$id]['cantidad'] + $cantidad;
            $carrito[$id]['cantidad'] = min($nueva, $producto['stock']);
        } else {
            $carrito[$id] = [
                'id'      => $producto['id'],
                'nombre'  => $producto['nombre'],
                'precio'  => (float) $producto['precio'],
                'stock'   => $producto['stock'],
                'cantidad'=> $cantidad,
                'imagen'  => $imagen,
            ];
        }

        session(['carrito' => $carrito]);

        return redirect()->route('carrito.index')
            ->with('success', '«' . $producto['nombre'] . '» añadido al carrito.');
    }

    public function actualizar(Request $request, int $id)
    {
        $request->validate(['cantidad' => 'required|integer|min:1|max:99']);

        $carrito = session('carrito', []);

        if (!isset($carrito[$id])) {
            return redirect()->route('carrito.index')->with('error', 'Producto no encontrado en el carrito.');
        }

        $carrito[$id]['cantidad'] = min((int) $request->input('cantidad'), $carrito[$id]['stock']);
        session(['carrito' => $carrito]);

        return redirect()->route('carrito.index')->with('success', 'Cantidad actualizada.');
    }

    public function eliminar(int $id)
    {
        $carrito = session('carrito', []);
        unset($carrito[$id]);
        session(['carrito' => $carrito]);

        return redirect()->route('carrito.index')->with('success', 'Producto eliminado del carrito.');
    }

    public function vaciar()
    {
        session()->forget('carrito');
        return redirect()->route('carrito.index')->with('success', 'Carrito vaciado.');
    }
}
