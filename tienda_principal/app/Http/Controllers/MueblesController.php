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
        $perPage = (int) ($params['per_page'] ?? 12);
        $params['per_page'] = in_array($perPage, [12, 24, 48]) ? $perPage : 12;

        $respuesta  = $this->mueblesService->getAllMuebles($params);
        $muebles    = $respuesta['data'] ?? [];
        $meta       = $respuesta['meta'] ?? $respuesta; // compatibilidad: sin Resources usa top-level
        $categorias = $this->mueblesService->getCategorias();

        $paginacion = [
            'current_page' => $meta['current_page'] ?? 1,
            'last_page'    => $meta['last_page']    ?? 1,
            'total'        => $meta['total']        ?? 0,
            'from'         => $meta['from']         ?? ($muebles ? 1 : 0),
            'to'           => $meta['to']           ?? count($muebles),
            'per_page'     => $meta['per_page']     ?? $params['per_page'],
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
