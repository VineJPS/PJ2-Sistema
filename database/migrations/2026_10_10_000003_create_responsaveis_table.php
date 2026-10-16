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
        Schema::create('responsaveis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('endereco_id')->constrained('enderecos');
            $table->string('nome', 150);
            $table->string('telefone', 20);
            $table->string('profissao', 100)->nullable();
            $table->string('grau_escolaridade', 50)->nullable();
            $table->decimal('salario', 10, 2)->nullable();
            $table->string('nis', 20)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('responsaveis');
    }
};
