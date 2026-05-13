<?php

namespace App\Http\Controllers;

use App\Services\UsuariosService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected $usuariosService;

    public function __construct(UsuariosService $usuariosService)
    {
        $this->usuariosService = $usuariosService;
    }

    public function showLogin()
    {
        if (session('auth_token')) {
            return redirect()->route('profile');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $result = $this->usuariosService->login($request->only('email', 'password'));

        if (!$result['success']) {
            $mensaje = $result['data']['mensaje'] ?? 'Credenciales incorrectas.';
            return back()->withErrors(['email' => $mensaje])->withInput();
        }

        session([
            'auth_token'  => $result['data']['token'],
            'auth_user'   => $result['data']['usuario'],
            'auth_abilities' => $result['data']['abilities'] ?? [],
        ]);

        return redirect()->route('profile');
    }

    public function showRegister()
    {
        if (session('auth_token')) {
            return redirect()->route('profile');
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nombre'            => 'required|string|max:100',
            'apellidos'         => 'required|string|max:150',
            'email'             => 'required|email|max:150',
            'password'          => 'required|string|min:6|confirmed',
        ]);

        $result = $this->usuariosService->register($request->only(
            'nombre', 'apellidos', 'email', 'password', 'password_confirmation'
        ));

        if (!$result['success']) {
            $errores = $result['data']['errores'] ?? [];
            return back()->withErrors($errores)->withInput();
        }

        session([
            'auth_token' => $result['data']['token'],
            'auth_user'  => $result['data']['usuario'],
            'auth_abilities' => [],
        ]);

        return redirect()->route('profile');
    }

    public function logout(Request $request)
    {
        $token = session('auth_token');

        if ($token) {
            $this->usuariosService->logout($token);
        }

        $request->session()->forget(['auth_token', 'auth_user', 'auth_abilities']);

        return redirect()->route('login');
    }
}
