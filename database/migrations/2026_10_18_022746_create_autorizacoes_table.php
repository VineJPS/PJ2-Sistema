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
        Schema::create('autorizacoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('crianca_id')->unique()->constrained('criancas')->cascadeOnDelete();
            $table->boolean('autoriza_frequencia')->default(false);
            $table->boolean('sai_sozinho')->default(false);
            $table->timestamps();
        });

        Schema::create('pessoas_autorizadas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('autorizacao_id')->constrained('autorizacoes')->cascadeOnDelete();
            $table->string('nome', 150);
            $table->string('parentesco', 100);
            $table->string('telefone', 20);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pessoas_autorizadas');
        Schema::dropIfExists('autorizacoes');
    }
};
