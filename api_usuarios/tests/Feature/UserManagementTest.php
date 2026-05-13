<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $adminToken;

    protected function setUp(): void
    {
        parent::setUp();
        Role::create(['nombre' => 'Administrador']);
        Role::create(['nombre' => 'Cliente']);

        $this->admin = User::create([
            'rol_id' => Role::where('nombre', 'Administrador')->first()->id,
            'nombre' => 'Admin',
            'apellidos' => 'System',
            'email' => 'admin@test.com',
            'password' => 'secret'
        ]);

        $this->adminToken = $this->admin->createToken('admin-token', [
            'usuarios.ver', 'usuarios.crear', 'usuarios.editar', 'usuarios.eliminar'
        ])->plainTextToken;
    }

    public function test_admin_can_list_users()
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->adminToken)
            ->getJson('/api/v1/usuarios');

        $response->assertStatus(200);
    }

    public function test_non_admin_cannot_list_users()
    {
        $user = User::create([
            'rol_id' => Role::where('nombre', 'Cliente')->first()->id,
            'nombre' => 'Cliente',
            'apellidos' => 'Test',
            'email' => 'cliente@test.com',
            'password' => 'secret'
        ]);

        $token = $user->createToken('token', ['perfil.ver'])->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/v1/usuarios');

        $response->assertStatus(403);
    }

    public function test_admin_can_create_user()
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->adminToken)
            ->postJson('/api/v1/usuarios', [
                'rol_id' => Role::where('nombre', 'Cliente')->first()->id,
                'nombre' => 'Nuevo',
                'apellidos' => 'Usuario',
                'email' => 'nuevo@test.com',
                'password' => 'password123'
            ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('users', ['email' => 'nuevo@test.com']);
    }

    public function test_admin_can_update_user()
    {
        $user = User::create([
            'rol_id' => Role::where('nombre', 'Cliente')->first()->id,
            'nombre' => 'Antiguo', 'apellidos' => 'Test', 'email' => 'antiguo@test.com', 'password' => 'secret'
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->adminToken)
            ->putJson("/api/v1/usuarios/{$user->id}", [
                'nombre' => 'Cambiado'
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('users', ['id' => $user->id, 'nombre' => 'Cambiado']);
    }

    public function test_admin_can_delete_user()
    {
        $user = User::create([
            'rol_id' => Role::where('nombre', 'Cliente')->first()->id,
            'nombre' => 'AEliminar', 'apellidos' => 'Test', 'email' => 'aeliminar@test.com', 'password' => 'secret'
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->adminToken)
            ->deleteJson("/api/v1/usuarios/{$user->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    public function test_admin_cannot_delete_self()
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->adminToken)
            ->deleteJson('/api/v1/usuarios/' . $this->admin->id);

        $response->assertStatus(422)
            ->assertJson(['mensaje' => 'No puedes eliminarte a ti mismo']);
    }
}
