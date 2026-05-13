<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class UsuariosService
{
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.api_usuarios.url');
    }

    public function register(array $data): array
    {
        try {
            $response = Http::post("{$this->baseUrl}/registrar", $data);
            return [
                'success' => $response->successful(),
                'status'  => $response->status(),
                'data'    => $response->json(),
            ];
        } catch (\Throwable) {
            return [
                'success' => false,
                'status'  => 500,
                'data'    => ['mensaje' => 'Error de conexión con el servicio de usuarios.'],
            ];
        }
    }

    public function login(array $credentials): array
    {
        try {
            $response = Http::post("{$this->baseUrl}/login", $credentials);
            return [
                'success' => $response->successful(),
                'status'  => $response->status(),
                'data'    => $response->json(),
            ];
        } catch (\Throwable) {
            return [
                'success' => false,
                'status'  => 500,
                'data'    => ['mensaje' => 'Error de conexión con el servicio de usuarios.'],
            ];
        }
    }

    public function getPerfil(string $token): array
    {
        try {
            $response = Http::withToken($token)->get("{$this->baseUrl}/perfil");
            return [
                'success' => $response->successful(),
                'status'  => $response->status(),
                'data'    => $response->json(),
            ];
        } catch (\Throwable) {
            return [
                'success' => false,
                'status'  => 500,
                'data'    => ['mensaje' => 'Error de conexión con el servicio de usuarios.'],
            ];
        }
    }

    public function logout(string $token): bool
    {
        try {
            $response = Http::withToken($token)->post("{$this->baseUrl}/logout");
            return $response->successful();
        } catch (\Throwable) {
            return false;
        }
    }

    // ─── Métodos de administración ───────────────────────────────────────────

    public function getUsuarios(string $token): array
    {
        try {
            $response = Http::withToken($token)->get("{$this->baseUrl}/usuarios");
            return $response->successful() ? ($response->json()['data'] ?? $response->json()) : [];
        } catch (\Throwable) {
            return [];
        }
    }

    public function getUsuarioById(int $id, string $token): array|null
    {
        try {
            $response = Http::withToken($token)->get("{$this->baseUrl}/usuarios/{$id}");
            return $response->successful() ? ($response->json()['usuario'] ?? $response->json()) : null;
        } catch (\Throwable) {
            return null;
        }
    }

    public function createUsuario(array $data, string $token): array
    {
        try {
            $response = Http::withToken($token)->post("{$this->baseUrl}/usuarios", $data);
            return [
                'success' => $response->successful(),
                'status'  => $response->status(),
                'data'    => $response->json(),
            ];
        } catch (\Throwable) {
            return ['success' => false, 'status' => 500, 'data' => ['mensaje' => 'Error de conexión.']];
        }
    }

    public function updateUsuario(int $id, array $data, string $token): array
    {
        try {
            $response = Http::withToken($token)->put("{$this->baseUrl}/usuarios/{$id}", $data);
            return [
                'success' => $response->successful(),
                'status'  => $response->status(),
                'data'    => $response->json(),
            ];
        } catch (\Throwable) {
            return ['success' => false, 'status' => 500, 'data' => ['mensaje' => 'Error de conexión.']];
        }
    }

    public function deleteUsuario(int $id, string $token): bool
    {
        try {
            $response = Http::withToken($token)->delete("{$this->baseUrl}/usuarios/{$id}");
            return $response->successful();
        } catch (\Throwable) {
            return false;
        }
    }

    public function getRoles(string $token): array
    {
        try {
            // Intentamos obtener un usuario para inferir roles; si la API expone /roles lo usamos
            $response = Http::withToken($token)->get("{$this->baseUrl}/roles");
            if ($response->successful()) {
                return $response->json()['data'] ?? $response->json();
            }
        } catch (\Throwable) {}
        // Fallback con roles conocidos del sistema
        return [
            ['id' => 1, 'nombre' => 'Administrador'],
            ['id' => 2, 'nombre' => 'Gestor'],
            ['id' => 3, 'nombre' => 'Cliente'],
        ];
    }
}
