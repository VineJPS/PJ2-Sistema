<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Adiciona campos da aba Saúde que estavam ausentes nas tabelas de anamnese.
     *
     * fichas_anamnese:
     *   - medico_assistente, medico_telefone
     *
     * anamnese_historico_clinicos:
     *   - instrucao_alergia (instrução de emergência sobre a alergia)
     *
     * anamnese_paths (seção Cicatrização / Anestesia / Hemorragia):
     *   - problema_cicatrizacao, alerta_anestesia, hemorragia
     *   - doencas_especificas (JSON: lista de {nome, selecionado})
     */
    public function up(): void
    {
        Schema::table('fichas_anamnese', function (Blueprint $table) {
            $table->string('medico_assistente', 200)->nullable()->after('outras_informacoes');
            $table->string('medico_telefone', 30)->nullable()->after('medico_assistente');
        });

        Schema::table('anamnese_historico_clinicos', function (Blueprint $table) {
            $table->text('instrucao_alergia')->nullable()->after('quais_alergias');
        });

        Schema::table('anamnese_paths', function (Blueprint $table) {
            $table->boolean('problema_cicatrizacao')->default(false)->after('observacoes');
            $table->boolean('alerta_anestesia')->default(false)->after('problema_cicatrizacao');
            $table->boolean('hemorragia')->default(false)->after('alerta_anestesia');
            $table->json('doencas_especificas')->nullable()->after('hemorragia');
        });
    }

    public function down(): void
    {
        Schema::table('fichas_anamnese', function (Blueprint $table) {
            $table->dropColumn(['medico_assistente', 'medico_telefone']);
        });

        Schema::table('anamnese_historico_clinicos', function (Blueprint $table) {
            $table->dropColumn('instrucao_alergia');
        });

        Schema::table('anamnese_paths', function (Blueprint $table) {
            $table->dropColumn(['problema_cicatrizacao', 'alerta_anestesia', 'hemorragia', 'doencas_especificas']);
        });
    }
};
