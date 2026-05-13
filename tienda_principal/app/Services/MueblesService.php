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

    public function createMueble(array $data, string $token): bool
    {
        try {
            $response = Http::withToken($token)->post("{$this->baseUrl}/muebles", $data);
            return $response->successful();
        } catch (\Throwable) {
            return false;
        }
    }

    public function updateMueble(int $id, array $data, string $token): bool
    {
        try {
            $response = Http::withToken($token)->put("{$this->baseUrl}/muebles/{$id}", $data);
            return $response->successful();
        } catch (\Throwable) {
            return false;
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
}
