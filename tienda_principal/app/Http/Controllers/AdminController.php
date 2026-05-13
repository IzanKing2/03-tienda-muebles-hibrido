<?php

namespace App\Http\Controllers;

use App\Services\MueblesService;
use App\Services\UsuariosService;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct(
        protected MueblesService   $mueblesService,
        protected UsuariosService  $usuariosService
    ) {}

    // ─── Helper ──────────────────────────────────────────────────────────────

    private function requireAbility(string $ability): bool
    {
        $abilities = session('auth_abilities', []);
        return in_array($ability, $abilities);
    }

    private function token(): string
    {
        return session('auth_token', '');
    }

    private function denyIfCannot(string $ability)
    {
        if (!session('auth_token')) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión.');
        }
        if (!$this->requireAbility($ability)) {
            abort(403, 'No tienes permisos para acceder a esta sección.');
        }
        return null;
    }

    // ─── Dashboard ───────────────────────────────────────────────────────────

    public function dashboard()
    {
        if ($redirect = $this->denyIfCannot('admin.panel')) return $redirect;

        $muebles  = $this->mueblesService->getAllMuebles(['per_page' => 1]);
        $usuarios = $this->usuariosService->getUsuarios($this->token());

        $stats = [
            'total_productos' => $muebles['meta']['total'] ?? $muebles['total'] ?? count($muebles['data'] ?? []),
            'total_usuarios'  => count($usuarios),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    // ─── Gestión de Muebles ───────────────────────────────────────────────────

    public function muebleIndex()
    {
        if ($redirect = $this->denyIfCannot('muebles.ver')) return $redirect;

        $params   = ['per_page' => 20, 'page' => request('page', 1)];
        if (request('search')) $params['search'] = request('search');
        $response = $this->mueblesService->getAllMuebles($params);

        $muebles    = $response['data'] ?? [];
        $paginacion = $response['meta'] ?? ['current_page' => 1, 'last_page' => 1, 'total' => count($muebles)];

        return view('admin.muebles.index', compact('muebles', 'paginacion'));
    }

    public function muebleCreate()
    {
        if ($redirect = $this->denyIfCannot('muebles.crear')) return $redirect;
        $categorias = $this->mueblesService->getCategorias();
        return view('admin.muebles.form', ['mueble' => null, 'categorias' => $categorias]);
    }

    public function muebleStore(Request $request)
    {
        if ($redirect = $this->denyIfCannot('muebles.crear')) return $redirect;

        $data = $request->validate([
            'nombre'          => 'required|string|max:200',
            'descripcion'     => 'required|string',
            'precio'          => 'required|numeric|min:0',
            'stock'           => 'required|integer|min:0',
            'materiales'      => 'nullable|string',
            'dimensiones'     => 'nullable|string|max:100',
            'color_principal' => 'nullable|string|max:50',
            'destacado'       => 'nullable|boolean',
            'categorias'      => 'nullable|array',
            'categorias.*'    => 'integer',
        ]);

        $data['destacado'] = $request->boolean('destacado');
        $result = $this->mueblesService->createMueble($data, $this->token());

        if (!$result['success']) {
            return back()->withInput()->with('error', 'Error al crear el producto. Comprueba los datos.');
        }

        return redirect()->route('admin.muebles.index')
            ->with('success', 'Producto «' . $data['nombre'] . '» creado correctamente.');
    }

    public function muebleEdit(int $id)
    {
        if ($redirect = $this->denyIfCannot('muebles.editar')) return $redirect;

        $mueble     = $this->mueblesService->getMuebleById($id);
        $categorias = $this->mueblesService->getCategorias();

        if (!$mueble) {
            return redirect()->route('admin.muebles.index')->with('error', 'Producto no encontrado.');
        }

        $mueble = $mueble['data'] ?? $mueble;

        return view('admin.muebles.form', compact('mueble', 'categorias'));
    }

    public function muebleUpdate(Request $request, int $id)
    {
        if ($redirect = $this->denyIfCannot('muebles.editar')) return $redirect;

        $data = $request->validate([
            'nombre'          => 'required|string|max:200',
            'descripcion'     => 'required|string',
            'precio'          => 'required|numeric|min:0',
            'stock'           => 'required|integer|min:0',
            'materiales'      => 'nullable|string',
            'dimensiones'     => 'nullable|string|max:100',
            'color_principal' => 'nullable|string|max:50',
            'destacado'       => 'nullable|boolean',
            'categorias'      => 'nullable|array',
            'categorias.*'    => 'integer',
        ]);

        $data['destacado'] = $request->boolean('destacado');
        $result = $this->mueblesService->updateMueble($id, $data, $this->token());

        if (!$result['success']) {
            return back()->withInput()->with('error', 'Error al actualizar el producto.');
        }

        return redirect()->route('admin.muebles.index')
            ->with('success', 'Producto actualizado correctamente.');
    }

    public function muebleDestroy(int $id)
    {
        if ($redirect = $this->denyIfCannot('muebles.eliminar')) return $redirect;

        $ok = $this->mueblesService->deleteMueble($id, $this->token());

        return redirect()->route('admin.muebles.index')
            ->with($ok ? 'success' : 'error', $ok ? 'Producto eliminado.' : 'Error al eliminar el producto.');
    }

    // ─── Gestión de Usuarios ──────────────────────────────────────────────────

    public function usuarioIndex()
    {
        if ($redirect = $this->denyIfCannot('usuarios.ver')) return $redirect;
        $usuarios = $this->usuariosService->getUsuarios($this->token());
        return view('admin.usuarios.index', compact('usuarios'));
    }

    public function usuarioCreate()
    {
        if ($redirect = $this->denyIfCannot('usuarios.crear')) return $redirect;
        $roles = $this->usuariosService->getRoles($this->token());
        return view('admin.usuarios.form', ['usuario' => null, 'roles' => $roles]);
    }

    public function usuarioStore(Request $request)
    {
        if ($redirect = $this->denyIfCannot('usuarios.crear')) return $redirect;

        $data = $request->validate([
            'nombre'               => 'required|string|max:100',
            'apellidos'            => 'required|string|max:150',
            'email'                => 'required|email|max:150',
            'password'             => 'required|string|min:6|confirmed',
            'rol_id'               => 'required|integer',
        ]);

        $result = $this->usuariosService->createUsuario($data, $this->token());

        if (!$result['success']) {
            $errores = $result['data']['errores'] ?? [];
            return back()->withErrors($errores)->withInput()
                ->with('error', $result['data']['mensaje'] ?? 'Error al crear el usuario.');
        }

        return redirect()->route('admin.usuarios.index')
            ->with('success', 'Usuario creado correctamente.');
    }

    public function usuarioEdit(int $id)
    {
        if ($redirect = $this->denyIfCannot('usuarios.editar')) return $redirect;

        $usuario = $this->usuariosService->getUsuarioById($id, $this->token());
        $roles   = $this->usuariosService->getRoles($this->token());

        if (!$usuario) {
            return redirect()->route('admin.usuarios.index')->with('error', 'Usuario no encontrado.');
        }

        return view('admin.usuarios.form', compact('usuario', 'roles'));
    }

    public function usuarioUpdate(Request $request, int $id)
    {
        if ($redirect = $this->denyIfCannot('usuarios.editar')) return $redirect;

        $rules = [
            'nombre'    => 'required|string|max:100',
            'apellidos' => 'required|string|max:150',
            'email'     => 'required|email|max:150',
            'rol_id'    => 'required|integer',
        ];
        if ($request->filled('password')) {
            $rules['password'] = 'string|min:6|confirmed';
        }

        $data = $request->validate($rules);
        if (!$request->filled('password')) unset($data['password']);

        $result = $this->usuariosService->updateUsuario($id, $data, $this->token());

        if (!$result['success']) {
            return back()->withInput()->with('error', 'Error al actualizar el usuario.');
        }

        return redirect()->route('admin.usuarios.index')
            ->with('success', 'Usuario actualizado correctamente.');
    }

    public function usuarioDestroy(int $id)
    {
        if ($redirect = $this->denyIfCannot('usuarios.eliminar')) return $redirect;

        $me = session('auth_user')['id'] ?? null;
        if ($me == $id) {
            return redirect()->route('admin.usuarios.index')
                ->with('error', 'No puedes eliminar tu propia cuenta desde el panel.');
        }

        $ok = $this->usuariosService->deleteUsuario($id, $this->token());

        return redirect()->route('admin.usuarios.index')
            ->with($ok ? 'success' : 'error', $ok ? 'Usuario eliminado.' : 'Error al eliminar el usuario.');
    }
}
