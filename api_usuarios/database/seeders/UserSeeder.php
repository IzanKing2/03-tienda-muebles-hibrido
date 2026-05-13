<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = \App\Models\Role::where('nombre', 'Administrador')->first();
        $gestorRole = \App\Models\Role::where('nombre', 'Gestor')->first();
        $clienteRole = \App\Models\Role::where('nombre', 'Cliente')->first();

        \App\Models\User::create([
            'rol_id' => $adminRole->id,
            'nombre' => 'Admin',
            'apellidos' => 'Usuario',
            'email' => 'admin@tienda.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
        ]);

        \App\Models\User::create([
            'rol_id' => $gestorRole->id,
            'nombre' => 'Gestor',
            'apellidos' => 'Usuario',
            'email' => 'gestor@tienda.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
        ]);

        \App\Models\User::create([
            'rol_id' => $clienteRole->id,
            'nombre' => 'Cliente',
            'apellidos' => 'Usuario',
            'email' => 'cliente@tienda.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
        ]);
    }
}
