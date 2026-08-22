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

        // Tabela de sessões no padrão exigido pelo Laravel
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('tokens_redefinicao_senha');
        Schema::dropIfExists('usuarios');
    }
};