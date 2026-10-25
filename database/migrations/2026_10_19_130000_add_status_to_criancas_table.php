<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('criancas', function (Blueprint $table) {
            $table->string('status', 20)->default('Pendente')->after('turma_betel');
        });
    }

    public function down(): void
    {
        Schema::table('criancas', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
