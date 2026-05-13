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
        Schema::create('categoria_producto', function (Blueprint $table) {
            // Relacion con categorias
            $table->foreignId('categoria_id')
                ->constrained('categorias')
                ->onDelete('cascade'); // Si se borra la categoría, se borra la relación

            // Relación con productos
            $table->foreignId('producto_id')
                ->constrained('productos')
                ->onDelete('cascade'); // Si se borra el producto, se borra la relación

            // Primary key compuesta
            $table->primary(['categoria_id', 'producto_id']);

            // Índice inverso para búsquedas desde productos
            $table->index('producto_id');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categoria_producto');
    }
};
