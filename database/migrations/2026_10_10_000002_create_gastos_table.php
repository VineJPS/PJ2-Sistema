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
        Schema::create('gastos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('endereco_id')->constrained('enderecos')->cascadeOnDelete();
            $table->decimal('agua', 10, 2);
            $table->decimal('energia', 10, 2);
            $table->decimal('alimentacao', 10, 2);
            $table->decimal('gas', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gastos');
    }
};
