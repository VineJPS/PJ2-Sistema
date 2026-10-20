<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('criancas', function (Blueprint $table) {
            $table->string('foto_path', 255)->nullable()->after('nome');
            $table->string('periodo', 20)->nullable()->after('escola');
            $table->string('serie', 50)->nullable()->after('periodo');
            $table->string('turma_betel', 20)->nullable()->after('serie');
        });
    }

    public function down(): void
    {
        Schema::table('criancas', function (Blueprint $table) {
            $table->dropColumn(['foto_path', 'periodo', 'serie', 'turma_betel']);
        });
    }
};
