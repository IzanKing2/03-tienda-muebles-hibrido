<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('usuario_id')->index();
            $table->enum('estado', ['pendiente', 'pagado', 'enviado', 'entregado', 'cancelado'])
                  ->default('pendiente');
            $table->decimal('total', 10, 2);
            $table->string('nombre_cliente', 150);
            $table->string('email_cliente', 150);
            $table->string('direccion_entrega', 300);
            $table->string('telefono', 20)->nullable();
            $table->string('metodo_pago', 50)->default('tarjeta');
            $table->text('notas')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};
