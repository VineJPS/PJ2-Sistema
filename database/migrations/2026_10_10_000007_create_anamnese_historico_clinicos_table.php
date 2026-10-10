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
        Schema::create('anamnese_historico_clinicos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ficha_anamnese_id')->constrained('fichas_anamnese')->cascadeOnDelete();
            $table->boolean('teve_doenca')->default(false);
            $table->text('quais_doencas')->nullable();
            $table->boolean('em_tratamento_medico')->default(false);
            $table->text('qual_tratamento')->nullable();
            $table->boolean('esta_gravida')->default(false);
            $table->boolean('usa_medicacao')->default(false);
            $table->text('quais_medicacoes')->nullable();
            $table->boolean('tem_alergia')->default(false);
            $table->text('quais_alergias')->nullable();
            $table->boolean('ja_foi_operado')->default(false);
            $table->text('quais_cirurgias')->nullable();
            $table->boolean('problema_visao')->default(false);
            $table->boolean('problema_audicao')->default(false);
            $table->boolean('problema_fala')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anamnese_historico_clinicos');
    }
};
