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
        Schema::create('relatos', function (Blueprint $table) {
            $table->id();
            $table->string('nome_crianca');
            $table->string('nome_funcionario');
            $table->foreignId('FK_id_usuario')->nullable()->constrained('usuarios')->nullOnDelete();
            $table->text('relato');
            $table->date('data_ocorrido');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('relatos');
    }
};
