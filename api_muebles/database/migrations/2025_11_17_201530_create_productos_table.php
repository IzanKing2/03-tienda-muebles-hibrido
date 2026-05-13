<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();

            // Información básica
            $table->string('nombre', 200);
            $table->text('descripcion');
            $table->decimal('precio', 10, 2); // Dos decimales
            $table->integer('stock')->default(0);

            // Características del mueble
            $table->text('materiales');
            $table->string('dimensiones', 100);
            $table->string('color_principal', 50);

            // Destacado en catálogo
            $table->boolean('destacado')->default(false);

            // Imagen principal (fuera de galería)
            $table->string('imagen_principal')->nullable();

            $table->timestamps();

            // Índices para filtros y búsquedas
            $table->index('destacado');
            $table->index('precio');
            $table->index('color_principal');


            if (config('database.default') !== 'sqlite') {
                $table->fullText(['nombre', 'descripcion']); // Búsqueda por texto
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
