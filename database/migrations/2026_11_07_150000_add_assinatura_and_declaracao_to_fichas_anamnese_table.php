<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('fichas_anamnese', function (Blueprint $table) {
            $table->string('motivo_atendimento', 255)->nullable()->default('Matrícula')->change();
            $table->boolean('declaracao_aceita')->default(false)->after('data_preenchimento');
            $table->string('assinatura_path', 255)->nullable()->after('declaracao_aceita');
        });
    }

    public function down(): void
    {
        Schema::table('fichas_anamnese', function (Blueprint $table) {
            $table->dropColumn(['declaracao_aceita', 'assinatura_path']);
        });
    }
};
