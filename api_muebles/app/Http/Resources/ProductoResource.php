<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'nombre'           => $this->nombre,
            'descripcion'      => $this->descripcion,
            'precio'           => (float) $this->precio,
            'stock'            => (int) $this->stock,
            'materiales'       => $this->materiales,
            'dimensiones'      => $this->dimensiones,
            'color_principal'  => $this->color_principal,
            'destacado'        => (bool) $this->destacado,
            'imagen_principal' => $this->imagen_principal,
            'categorias'       => CategoriaResource::collection($this->whenLoaded('categorias')),
            'galeria'          => $this->whenLoaded('galeria', fn() => [
                'id'       => $this->galeria->id,
                'imagenes' => ($this->galeria->imagenes ?? collect())->map(fn($img) => [
                    'id'           => $img->id,
                    'ruta'         => $img->ruta,
                    'es_principal' => (bool) $img->es_principal,
                ]),
            ]),
            'created_at'       => $this->created_at?->toIso8601String(),
            'updated_at'       => $this->updated_at?->toIso8601String(),
        ];
    }
}
