<?php

namespace App\Http\Controllers;

use App\Models\Carrito;
use App\Models\Pedido;
use App\Models\PedidoItem;
use App\Services\MueblesService;
use Illuminate\Http\Request;

class PedidoController extends Controller
{
    /**
     * Inyectamos el servicio de muebles para poder actualizar el stock
     * de los productos al confirmar una compra.
     *
     * ¿Qué es la inyección de dependencias?
     * Laravel automáticamente pasa una instancia de MueblesService
     * cuando crea este controlador, sin que tengamos que hacerlo manualmente.
     */
    public function __construct(protected MueblesService $mueblesService) {}

    private function requireAuth()
    {
        if (!session('auth_token')) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para continuar.');
        }
        return null;
    }

    // ─── Formulario de checkout ───────────────────────────────────────────────
    public function checkout()
    {
        if ($redir = $this->requireAuth()) return $redir;

        $usuarioId = session('auth_user')['id'];
        $carrito   = Carrito::where('usuario_id', $usuarioId)->with('items')->first();

        if (!$carrito || $carrito->items->isEmpty()) {
            return redirect()->route('carrito.index')
                ->with('error', 'Tu carrito está vacío. Añade productos antes de continuar.');
        }

        $usuario = session('auth_user');

        return view('checkout', compact('carrito', 'usuario'));
    }

    // ─── Crear pedido desde el carrito ───────────────────────────────────────
    public function store(Request $request)
    {
        if ($redir = $this->requireAuth()) return $redir;

        $request->validate([
            'nombre_cliente'   => 'required|string|max:150',
            'email_cliente'    => 'required|email|max:150',
            'direccion_entrega'=> 'required|string|max:300',
            'telefono'         => 'nullable|string|max:20',
            'metodo_pago'      => 'required|in:tarjeta,transferencia,efectivo',
            'notas'            => 'nullable|string|max:500',
        ]);

        $usuarioId = session('auth_user')['id'];
        $carrito   = Carrito::where('usuario_id', $usuarioId)->with('items')->first();

        if (!$carrito || $carrito->items->isEmpty()) {
            return redirect()->route('carrito.index')
                ->with('error', 'Tu carrito está vacío.');
        }

        $total = $carrito->total();

        $pedido = Pedido::create([
            'usuario_id'       => $usuarioId,
            'estado'           => 'pendiente',
            'total'            => $total,
            'nombre_cliente'   => $request->nombre_cliente,
            'email_cliente'    => $request->email_cliente,
            'direccion_entrega'=> $request->direccion_entrega,
            'telefono'         => $request->telefono,
            'metodo_pago'      => $request->metodo_pago,
            'notas'            => $request->notas,
        ]);

        // Obtener el token de autenticación del usuario para poder
        // comunicarnos con la API de muebles (necesita permisos)
        $token = session('auth_token');

        foreach ($carrito->items as $item) {
            // Crear el registro de cada producto en la tabla de items del pedido
            PedidoItem::create([
                'pedido_id'  => $pedido->id,
                'producto_id'=> $item->producto_id,
                'nombre'     => $item->nombre,
                'precio'     => $item->precio,
                'cantidad'   => $item->cantidad,
            ]);

            // ── DESCONTAR STOCK ──────────────────────────────────────────
            // Por cada producto comprado, restamos la cantidad del stock
            // en la API de muebles. Así el inventario se mantiene actualizado.
            $this->mueblesService->updateStock(
                $item->producto_id,
                $item->cantidad,
                $token
            );
        }

        $carrito->items()->delete();

        return redirect()->route('pedidos.show', $pedido->id)
            ->with('success', '¡Pedido #' . $pedido->id . ' realizado correctamente! Gracias por tu compra.');
    }

    // ─── Lista de pedidos del usuario ─────────────────────────────────────────
    public function index()
    {
        if ($redir = $this->requireAuth()) return $redir;

        $usuarioId = session('auth_user')['id'];
        $pedidos   = Pedido::where('usuario_id', $usuarioId)
            ->withCount('items')
            ->orderByDesc('created_at')
            ->get();

        return view('pedidos.index', compact('pedidos'));
    }

    // ─── Detalle de un pedido ─────────────────────────────────────────────────
    public function show(int $id)
    {
        if ($redir = $this->requireAuth()) return $redir;

        $usuarioId = session('auth_user')['id'];
        $pedido    = Pedido::where('id', $id)
            ->where('usuario_id', $usuarioId)
            ->with('items')
            ->first();

        if (!$pedido) {
            return redirect()->route('pedidos.index')
                ->with('error', 'Pedido no encontrado.');
        }

        return view('pedidos.show', compact('pedido'));
    }
}
