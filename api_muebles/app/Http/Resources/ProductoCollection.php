<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductoCollection extends ResourceCollection
{
    public $collects = ProductoResource::class;
}
