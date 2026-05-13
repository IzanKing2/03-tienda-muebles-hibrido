<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MueblesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categoriaSillas = \App\Models\Categoria::create(['nombre' => 'Sillas', 'descripcion' => 'Sillas de todo tipo']);
        $categoriaMesas = \App\Models\Categoria::create(['nombre' => 'Mesas', 'descripcion' => 'Mesas de comedor y escritorio']);

        $producto = \App\Models\Producto::create([
            'nombre' => 'Silla Nórdica',
            'descripcion' => 'Una silla de diseño nórdico muy elegante',
            'precio' => 45.99,
            'stock' => 10,
            'materiales' => 'Madera y tela',
            'dimensiones' => '50x50x80 cm',
            'color_principal' => 'Blanco',
            'destacado' => true,
        ]);

        $producto->categorias()->attach($categoriaSillas->id);
    }
}
