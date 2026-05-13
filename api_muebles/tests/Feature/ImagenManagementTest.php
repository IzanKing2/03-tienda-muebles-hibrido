<?php

namespace Tests\Feature;

use App\Models\Producto;
use App\Models\User;
use App\Models\Imagen;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ImagenManagementTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $token;
    protected $producto;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');

        $this->user = User::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => 'secret'
        ]);

        $this->token = $this->user->createToken('test', ['muebles.editar'])->plainTextToken;

        $this->producto = Producto::create([
            'nombre' => 'Mueble Test',
            'descripcion' => 'Desc',
            'precio' => 10,
            'stock' => 5,
            'materiales' => 'Madera',
            'dimensiones' => '1x1',
            'color_principal' => 'Rojo'
        ]);
    }

    public function test_can_upload_image()
    {
        $file = UploadedFile::fake()->image('mueble.jpg');

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson("/api/v1/muebles/{$this->producto->id}/imagenes", [
                'imagen' => $file
            ]);

        $response->assertStatus(201);
        Storage::disk('public')->assertExists('imagenes/' . $file->hashName());
        $this->assertDatabaseHas('imagenes', ['ruta' => 'imagenes/' . $file->hashName()]);
    }

    public function test_first_image_is_principal()
    {
        $file = UploadedFile::fake()->image('mueble.jpg');

        $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson("/api/v1/muebles/{$this->producto->id}/imagenes", ['imagen' => $file]);

        $this->producto->refresh();
        $this->assertEquals('imagenes/' . $file->hashName(), $this->producto->imagen_principal);
    }

    public function test_can_delete_image_and_file()
    {
        $file = UploadedFile::fake()->image('mueble.jpg');
        $path = $file->store('imagenes', 'public');
        
        $galeria = $this->producto->galeria()->create();
        $imagen = $galeria->imagenes()->create(['ruta' => $path, 'es_principal' => true, 'orden' => 1]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->deleteJson("/api/v1/imagenes/{$imagen->id}");

        $response->assertStatus(200);
        Storage::disk('public')->assertMissing($path);
        $this->assertDatabaseMissing('imagenes', ['id' => $imagen->id]);
    }
}
