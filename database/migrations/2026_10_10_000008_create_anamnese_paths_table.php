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
        Schema::create('anamnese_paths', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ficha_anamnese_id')->constrained('fichas_anamnese')->cascadeOnDelete();
            $table->boolean('problemas_cardiacos')->default(false);
            $table->boolean('problemas_pulmonares')->default(false);
            $table->boolean('problemas_gastrointestinais')->default(false);
            $table->boolean('problemas_neurologicos')->default(false);
            $table->boolean('problemas_ortopedicos')->default(false);
            $table->boolean('problemas_alergicos')->default(false);
            $table->text('manchas')->nullable();
            $table->text('observacoes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anamnese_paths');
    }
};
