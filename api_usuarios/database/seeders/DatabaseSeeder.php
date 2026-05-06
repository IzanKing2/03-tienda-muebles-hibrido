<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Administrador
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@tienda.com',
            'password' => bcrypt('password'),
            'rol' => 'administrador'
        ]);

        // Gestor
        User::create([
            'name' => 'Gestor',
            'email' => 'gestor@tienda.com',
            'password' => bcrypt('password'),
            'rol' => 'gestor'
        ]);

        // Cliente
        User::create([
            'name' => 'Cliente Prueba',
            'email' => 'cliente@tienda.com',
            'password' => bcrypt('password'),
            'rol' => 'cliente'
        ]);
    }
}
