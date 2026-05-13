<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductoCollection extends ResourceCollection
{
    public $collects = ProductoResource::class;

    public function toArray(Request $request): array
    {
        return [
            'data'         => $this->collection,
            'current_page' => $this->currentPage(),
            'last_page'    => $this->lastPage(),
            'per_page'     => $this->perPage(),
            'total'        => $this->total(),
            'from'         => $this->firstItem(),
            'to'           => $this->lastItem(),
        ];
    }
}
