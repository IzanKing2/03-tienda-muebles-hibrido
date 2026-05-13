<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('imagenes', function (Blueprint $table) {
            $table->id();

            // Relación con galerías
            $table->foreignId('galeria_id')->constrained('galerias')->onDelete('cascade'); // Si se borra la galería, se borran sus imágenes

            // Ruta de la imagen
            $table->string('ruta');

            // Control de imagen principal
            $table->boolean('es_principal')->default(false);

            // Orden de visualización
            $table->integer('orden')->default(0);

            $table->timestamps();

            // Índices para ordenar y filtrar
            $table->index(['galeria_id', 'orden']); // Ordena imágenes de una galería
            $table->index(['galeria_id', 'es_principal']); // Encontrar la principal rápido
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('imagenes');
    }
};
