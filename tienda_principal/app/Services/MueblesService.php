<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class MueblesService
{
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.api_muebles.url');
    }

    public function getAllMuebles(array $params = []): array
    {
        try {
            $response = Http::get("{$this->baseUrl}/muebles", $params);
            return $response->successful() ? $response->json() : ['data' => []];
        } catch (\Throwable) {
            return ['data' => [], 'error' => 'Servicio de muebles no disponible.'];
        }
    }

    public function getMuebleById(int $id): array|null
    {
        try {
            $response = Http::get("{$this->baseUrl}/muebles/{$id}");
            return $response->successful() ? $response->json() : null;
        } catch (\Throwable) {
            return null;
        }
    }

    public function getCategorias(): array
    {
        try {
            $response = Http::get("{$this->baseUrl}/categorias");
            return $response->successful() ? ($response->json()['data'] ?? $response->json()) : [];
        } catch (\Throwable) {
            return [];
        }
    }

    public function createMueble(array $data, string $token): array
    {
        try {
            $response = Http::withToken($token)->post("{$this->baseUrl}/muebles", $data);
            return [
                'success' => $response->successful(),
                'data'    => $response->json(),
                'status'  => $response->status(),
            ];
        } catch (\Throwable) {
            return ['success' => false, 'data' => [], 'status' => 500];
        }
    }

    public function updateMueble(int $id, array $data, string $token): array
    {
        try {
            $response = Http::withToken($token)->put("{$this->baseUrl}/muebles/{$id}", $data);
            return [
                'success' => $response->successful(),
                'data'    => $response->json(),
                'status'  => $response->status(),
            ];
        } catch (\Throwable) {
            return ['success' => false, 'data' => [], 'status' => 500];
        }
    }

    public function deleteMueble(int $id, string $token): bool
    {
        try {
            $response = Http::withToken($token)->delete("{$this->baseUrl}/muebles/{$id}");
            return $response->successful();
        } catch (\Throwable) {
            return false;
        }
    }

    public function createCategoria(array $data, string $token): array
    {
        try {
            $response = Http::withToken($token)->post("{$this->baseUrl}/categorias", $data);
            return [
                'success' => $response->successful(),
                'data'    => $response->json(),
                'status'  => $response->status(),
            ];
        } catch (\Throwable) {
            return ['success' => false, 'data' => [], 'status' => 500];
        }
    }

    public function deleteCategoria(int $id, string $token): bool
    {
        try {
            $response = Http::withToken($token)->delete("{$this->baseUrl}/categorias/{$id}");
            return $response->successful();
        } catch (\Throwable) {
            return false;
        }
    }
}
