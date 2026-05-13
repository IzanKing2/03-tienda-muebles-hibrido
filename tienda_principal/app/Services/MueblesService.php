<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class MueblesService
{
    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = env('API_MUEBLES_URL');
    }

    public function getAllMuebles()
    {
        $response = Http::get("{$this->baseUrl}/muebles");
        return $response->successful() ? $response->json() : [];
    }

    public function getMuebleById($id)
    {
        $response = Http::get("{$this->baseUrl}/muebles/{$id}");
        return $response->successful() ? $response->json() : null;
    }

    public function createMueble($data, $token)
    {
        $response = Http::withToken($token)->post("{$this->baseUrl}/muebles", $data);
        return $response->successful();
    }

    public function updateMueble($id, $data, $token)
    {
        $response = Http::withToken($token)->put("{$this->baseUrl}/muebles/{$id}", $data);
        return $response->successful();
    }

    public function deleteMueble($id, $token)
    {
        $response = Http::withToken($token)->delete("{$this->baseUrl}/muebles/{$id}");
        return $response->successful();
    }
}
