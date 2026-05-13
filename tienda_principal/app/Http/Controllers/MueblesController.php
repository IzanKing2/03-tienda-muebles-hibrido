<?php

namespace App\Http\Controllers;

use App\Services\MueblesService;
use Illuminate\Http\Request;

class MueblesController extends Controller
{
    protected MueblesService $mueblesService;

    public function __construct(MueblesService $mueblesService)
    {
        $this->mueblesService = $mueblesService;
    }

    public function index(Request $request): \Illuminate\View\View
    {
        $params = $request->only(['search', 'categoria_id', 'precio_min', 'precio_max', 'sort', 'order', 'per_page']);
        $respuesta = $this->mueblesService->getAllMuebles($params);

        $muebles   = $respuesta['data']          ?? [];
        $paginacion = [
            'current_page' => $respuesta['current_page'] ?? 1,
            'last_page'    => $respuesta['last_page']    ?? 1,
            'total'        => $respuesta['total']        ?? 0,
        ];

        return view('muebles.index', compact('muebles', 'paginacion'));
    }

    public function show(int $id): \Illuminate\View\View|\Illuminate\Http\RedirectResponse
    {
        $mueble = $this->mueblesService->getMuebleById($id);

        if (!$mueble) {
            return redirect()->route('muebles.index')->with('error', 'Producto no encontrado.');
        }

        return view('muebles.show', compact('mueble'));
    }
}
