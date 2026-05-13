<?php

namespace App\Http\Controllers;

use App\Services\MueblesService;
use Illuminate\Http\Request;

class MueblesController extends Controller
{
    public function __construct(protected MueblesService $mueblesService) {}

    // ─── Página de inicio ─────────────────────────────────────────────────────
    public function home(): \Illuminate\View\View
    {
        $destacados = $this->mueblesService->getAllMuebles([
            'destacado' => 1,
            'per_page'  => 8,
            'sort'      => 'destacado',
            'order'     => 'desc',
        ])['data'] ?? [];

        $recientes = $this->mueblesService->getAllMuebles([
            'per_page' => 4,
            'sort'     => 'created_at',
            'order'    => 'desc',
        ])['data'] ?? [];

        $categorias = $this->mueblesService->getCategorias();

        return view('home', compact('destacados', 'recientes', 'categorias'));
    }

    // ─── Catálogo con filtros y paginación ────────────────────────────────────
    public function index(Request $request): \Illuminate\View\View
    {
        $params = $request->only([
            'search', 'categoria_id', 'precio_min', 'precio_max',
            'sort', 'order', 'per_page', 'page',
        ]);

        // Per-page válido
        $params['per_page'] = in_array((int)($params['per_page'] ?? 12), [12, 24, 48])
            ? (int) $params['per_page']
            : 12;

        $respuesta  = $this->mueblesService->getAllMuebles($params);
        $muebles    = $respuesta['data'] ?? [];
        $categorias = $this->mueblesService->getCategorias();

        $paginacion = [
            'current_page' => $respuesta['current_page'] ?? 1,
            'last_page'    => $respuesta['last_page']    ?? 1,
            'total'        => $respuesta['total']        ?? 0,
            'from'         => $respuesta['from']         ?? ($muebles ? 1 : 0),
            'to'           => $respuesta['to']           ?? count($muebles),
            'per_page'     => $respuesta['per_page']     ?? $params['per_page'],
        ];

        return view('muebles.index', compact('muebles', 'paginacion', 'categorias'));
    }

    // ─── Detalle de producto ──────────────────────────────────────────────────
    public function show(int $id): \Illuminate\View\View|\Illuminate\Http\RedirectResponse
    {
        $mueble = $this->mueblesService->getMuebleById($id);

        if (!$mueble) {
            return redirect()->route('muebles.index')->with('error', 'Producto no encontrado.');
        }

        // La API puede devolver el objeto directamente o bajo clave 'data'
        $mueble = $mueble['data'] ?? $mueble;

        return view('muebles.show', compact('mueble'));
    }
}
