<?php

namespace Tests\Feature;

use App\Models\Producto;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MueblesTest extends TestCase
{
    use RefreshDatabase;

    public function test_anyone_can_list_muebles()
    {
        Producto::create([
            'nombre' => 'Silla Test',
            'descripcion' => 'Desc',
            'precio' => 10,
            'stock' => 5,
            'materiales' => 'Madera',
            'dimensiones' => '1x1',
            'color_principal' => 'Rojo'
        ]);

        $response = $this->getJson('/api/v1/muebles');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data');
    }

    public function test_guest_cannot_create_mueble()
    {
        $response = $this->postJson('/api/v1/muebles', [
            'nombre' => 'Silla Intruso',
        ]);

        $response->assertStatus(401);
    }

    public function test_user_without_ability_cannot_create_mueble()
    {
        // We create a user in THIS database for Sanctum to work during the test
        $user = User::create([
            'name' => 'Cliente',
            'email' => 'cliente@test.com',
            'password' => 'secret'
        ]);

        // Token with only view abilities
        $token = $user->createToken('test', ['muebles.ver'])->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/v1/muebles', [
                'nombre' => 'Silla Prohibida',
            ]);

        $response->assertStatus(403);
    }

    public function test_user_with_ability_can_create_mueble()
    {
        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => 'secret'
        ]);

        $token = $user->createToken('test', ['muebles.crear'])->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/v1/muebles', [
                'nombre' => 'Silla Nueva',
                'descripcion' => 'Nueva desc',
                'precio' => 100,
                'stock' => 2,
                'materiales' => 'Metal',
                'dimensiones' => '2x2',
                'color_principal' => 'Azul'
            ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('productos', ['nombre' => 'Silla Nueva']);
    }

    public function test_user_with_ability_can_update_mueble()
    {
        $user = User::create(['name' => 'Gestor', 'email' => 'gestor@test.com', 'password' => 'secret']);
        $token = $user->createToken('test', ['muebles.editar'])->plainTextToken;

        $producto = Producto::create([
            'nombre' => 'Viejo', 'descripcion' => 'Desc', 'precio' => 10, 'stock' => 5, 
            'materiales' => 'Madera', 'dimensiones' => '1x1', 'color_principal' => 'Rojo'
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->putJson("/api/v1/muebles/{$producto->id}", [
                'nombre' => 'Actualizado'
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('productos', ['id' => $producto->id, 'nombre' => 'Actualizado']);
    }

    public function test_user_with_ability_can_delete_mueble()
    {
        $user = User::create(['name' => 'Admin', 'email' => 'admin@test.com', 'password' => 'secret']);
        $token = $user->createToken('test', ['muebles.eliminar'])->plainTextToken;

        $producto = Producto::create([
            'nombre' => 'AEliminar', 'descripcion' => 'Desc', 'precio' => 10, 'stock' => 5, 
            'materiales' => 'Madera', 'dimensiones' => '1x1', 'color_principal' => 'Rojo'
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->deleteJson("/api/v1/muebles/{$producto->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('productos', ['id' => $producto->id]);
    }
}
