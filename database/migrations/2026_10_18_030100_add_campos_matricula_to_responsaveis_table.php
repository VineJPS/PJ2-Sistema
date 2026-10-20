<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('responsaveis', function (Blueprint $table) {
            $table->string('endereco_comercial', 255)->nullable()->after('profissao');
            $table->string('religiao', 100)->nullable()->after('endereco_comercial');
        });
    }

    public function down(): void
    {
        Schema::table('responsaveis', function (Blueprint $table) {
            $table->dropColumn(['endereco_comercial', 'religiao']);
        });
    }
};
