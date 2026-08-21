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
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('cpf');
            $table->string('email')->unique();
            $table->date('data_nascimento');
            $table->foreignId('FK_id_nivel')->constrained('niveis');
            $table->timestamp('email_verificado_em')->nullable();
            $table->string('senha');
            $table->rememberToken(); // Mantém o token de "lembrar-me" do Laravel
            $table->timestamps(); // Cria 'created_at' e 'updated_at' 
        });

        Schema::create('tokens_redefinicao_senha', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('criado_em')->nullable();
        });

        Schema::create('sessoes', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('usuario_id')->nullable()->index();
            $table->string('endereco_ip', 45)->nullable();
            $table->text('agente_usuario')->nullable();
            $table->longText('conteudo');
            $table->integer('ultima_atividade')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessoes');
        Schema::dropIfExists('tokens_redefinicao_senha');
        Schema::dropIfExists('usuarios');
    }
};