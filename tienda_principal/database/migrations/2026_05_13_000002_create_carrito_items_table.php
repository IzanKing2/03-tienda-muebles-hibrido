<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('carrito_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('carrito_id')->constrained('carritos')->cascadeOnDelete();
            $table->unsignedBigInteger('producto_id'); // ID del producto en api_muebles
            $table->string('nombre', 200);             // snapshot del nombre al añadir
            $table->decimal('precio', 10, 2);          // snapshot del precio al añadir
            $table->unsignedSmallInteger('cantidad')->default(1);
            $table->string('imagen')->nullable();       // ruta de imagen principal
            $table->timestamps();

            $table->unique(['carrito_id', 'producto_id']); // un producto por línea
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('carrito_items');
    }
};
