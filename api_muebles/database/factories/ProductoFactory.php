<?php

namespace Database\Factories;

use App\Models\Producto;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductoFactory extends Factory
{
    protected $model = Producto::class;

    public function definition(): array
    {
        return [
            'nombre'          => ucfirst(fake()->words(fake()->numberBetween(2, 4), true)),
            'descripcion'     => fake()->paragraph(2),
            'precio'          => fake()->randomFloat(2, 29, 1999),
            'stock'           => fake()->numberBetween(0, 50),
            'materiales'      => fake()->randomElement(['Madera maciza', 'Metal', 'Tela', 'Cuero', 'MDF', 'Nogal', 'Pino']),
            'dimensiones'     => fake()->numerify('##') . 'cm x ' . fake()->numerify('##') . 'cm x ' . fake()->numerify('##') . 'cm',
            'color_principal' => fake()->randomElement(['Blanco', 'Negro', 'Gris', 'Marrón', 'Beige', 'Verde', 'Azul']),
            'destacado'       => fake()->boolean(20),
            'imagen_principal' => null,
        ];
    }

    public function destacado(): static
    {
        return $this->state(['destacado' => true]);
    }

    public function agotado(): static
    {
        return $this->state(['stock' => 0]);
    }
}
