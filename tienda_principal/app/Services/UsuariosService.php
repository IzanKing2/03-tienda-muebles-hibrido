<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class UsuariosService
{
    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = env('API_USUARIOS_URL', 'http://127.0.0.1:8001/api/v1');
    }

    public function register($data)
    {
        $response = Http::post("{$this->baseUrl}/registrar", $data);
        return [
            'success' => $response->successful(),
            'status' => $response->status(),
            'data' => $response->json()
        ];
    }

    public function login($credentials)
    {
        $response = Http::post("{$this->baseUrl}/login", $credentials);
        return [
            'success' => $response->successful(),
            'status' => $response->status(),
            'data' => $response->json()
        ];
    }

    public function getPerfil($token)
    {
        $response = Http::withToken($token)->get("{$this->baseUrl}/perfil");
        return [
            'success' => $response->successful(),
            'status' => $response->status(),
            'data' => $response->json()
        ];
    }

    public function logout($token)
    {
        $response = Http::withToken($token)->post("{$this->baseUrl}/logout");
        return $response->successful();
    }
}
