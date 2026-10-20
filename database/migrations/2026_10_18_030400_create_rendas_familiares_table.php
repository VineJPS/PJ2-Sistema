<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rendas_familiares', function (Blueprint $table) {
            $table->id();
            $table->foreignId('crianca_id')->unique()->constrained('criancas')->cascadeOnDelete();
            $table->decimal('renda_total', 10, 2);
            $table->decimal('renda_per_capita', 10, 2);
            $table->boolean('recebe_beneficio')->default(false);
            $table->string('beneficio_nis', 20)->nullable();
            $table->string('beneficio_tipo', 50)->nullable();
            $table->decimal('beneficio_valor', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rendas_familiares');
    }
};
