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
        Schema::create('fichas_anamnese', function (Blueprint $table) {
            $table->id();
            $table->foreignId('crianca_id')->constrained('criancas')->cascadeOnDelete();
            $table->text('queixa_principal')->nullable();
            $table->string('motivo_atendimento', 255);
            $table->text('habitos')->nullable();
            $table->text('antecedentes_familiares')->nullable();
            $table->text('outras_informacoes')->nullable();
            $table->date('data_preenchimento');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fichas_anamnese');
    }
};
