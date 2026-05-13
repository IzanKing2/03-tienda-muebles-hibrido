<?php

namespace App\Http\Controllers;

use App\Services\UsuariosService;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    protected $usuariosService;

    public function __construct(UsuariosService $usuariosService)
    {
        $this->usuariosService = $usuariosService;
    }

    public function index(Request $request)
    {
        $token = session('auth_token');

        if (!$token) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para ver tu perfil.');
        }

        $perfilResponse = $this->usuariosService->getPerfil($token);

        if (!$perfilResponse['success']) {
            return redirect()->route('login')->with('error', 'Sesión expirada o inválida.');
        }

        $usuario = $perfilResponse['data']['usuario'];

        return view('profile.index', compact('usuario'));
    }
}
