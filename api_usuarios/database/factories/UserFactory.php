<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'rol_id'    => 3,
            'nombre'    => fake()->firstName(),
            'apellidos' => fake()->lastName() . ' ' . fake()->lastName(),
            'email'     => fake()->unique()->safeEmail(),
            'password'  => Hash::make('password'),
        ];
    }

    public function administrador(): static
    {
        return $this->state(['rol_id' => 1]);
    }

    public function gestor(): static
    {
        return $this->state(['rol_id' => 2]);
    }

    public function cliente(): static
    {
        return $this->state(['rol_id' => 3]);
    }
}
