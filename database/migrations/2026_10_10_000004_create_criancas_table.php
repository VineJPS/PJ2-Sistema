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
        Schema::create('criancas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('endereco_id')->constrained('enderecos');
            $table->string('nome', 150);
            $table->string('cpf', 14)->nullable()->unique();
            $table->string('rg', 20)->nullable();
            $table->date('data_nascimento');
            $table->string('escola', 150)->nullable();
            $table->text('relatos')->nullable();
            $table->boolean('conselho_tutelar')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('criancas');
    }
};
