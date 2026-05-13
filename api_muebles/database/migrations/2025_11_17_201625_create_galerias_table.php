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
        Schema::create('galerias', function (Blueprint $table) {
            $table->id();

            // Relación con productos (1:1)
            $table->foreignId('producto_id')->constrained('productos')->onDelete('cascade'); // Si se borra el producto, se borra su galería

            $table->timestamps();

            // Un producto solo tiene UNA galería
            $table->unique('producto_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('galerias');
    }
};
