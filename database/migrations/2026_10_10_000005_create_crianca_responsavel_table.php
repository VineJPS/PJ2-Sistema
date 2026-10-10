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
        Schema::create('crianca_responsavel', function (Blueprint $table) {
            $table->foreignId('crianca_id')->constrained('criancas')->cascadeOnDelete();
            $table->foreignId('responsavel_id')->constrained('responsaveis')->cascadeOnDelete();
            $table->string('grau_parentesco', 50);
            $table->primary(['crianca_id', 'responsavel_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crianca_responsavel');
    }
};
