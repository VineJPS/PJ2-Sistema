<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('membros_familiares', function (Blueprint $table) {
            $table->id();
            $table->foreignId('crianca_id')->constrained('criancas')->cascadeOnDelete();
            $table->string('nome', 150);
            $table->string('parentesco', 100);
            $table->date('data_nascimento')->nullable();
            $table->decimal('renda', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('membros_familiares');
    }
};
