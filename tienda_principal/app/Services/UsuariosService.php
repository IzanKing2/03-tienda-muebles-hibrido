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
}
