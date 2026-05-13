<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Seed roles for testing
        Role::create(['nombre' => 'Administrador']);
        Role::create(['nombre' => 'Gestor']);
        Role::create(['nombre' => 'Cliente']);
    }

    public function test_user_can_register()
    {
        $response = $this->postJson('/api/v1/registrar', [
            'nombre' => 'Diego',
            'apellidos' => 'Cuba',
            'email' => 'diego@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['mensaje', 'usuario', 'token']);
        
        $this->assertDatabaseHas('users', ['email' => 'diego@example.com']);
    }

    public function test_user_can_login()
    {
        $user = User::create([
            'rol_id' => Role::where('nombre', 'Cliente')->first()->id,
            'nombre' => 'Test',
            'apellidos' => 'User',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
        ]);

        $response = $this->postJson('/api/v1/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['mensaje', 'usuario', 'token', 'abilities']);
        
        $this->assertContains('muebles.ver', $response->json('abilities'));
    }

    public function test_user_can_get_profile()
    {
        $user = User::create([
            'rol_id' => Role::where('nombre', 'Cliente')->first()->id,
            'nombre' => 'Test',
            'apellidos' => 'User',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
        ]);

        $token = $user->createToken('test')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/v1/perfil');

        $response->assertStatus(200)
            ->assertJsonPath('usuario.email', 'test@example.com');
    }

    public function test_user_can_logout()
    {
        $user = User::create([
            'rol_id' => Role::where('nombre', 'Cliente')->first()->id,
            'nombre' => 'Test',
            'apellidos' => 'User',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
        ]);

        $token = $user->createToken('test')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/v1/logout');

        $response->assertStatus(200);
        $this->assertCount(0, $user->tokens);
    }
}
