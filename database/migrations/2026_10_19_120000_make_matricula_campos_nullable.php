<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gastos', function (Blueprint $table) {
            $table->decimal('agua', 10, 2)->nullable()->change();
            $table->decimal('energia', 10, 2)->nullable()->change();
            $table->decimal('gas', 10, 2)->nullable()->change();
            $table->decimal('alimentacao', 10, 2)->nullable()->change();
        });

        Schema::table('assinaturas', function (Blueprint $table) {
            $table->date('data_assinatura')->nullable()->change();
            $table->string('arquivo_path', 255)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('gastos', function (Blueprint $table) {
            $table->decimal('agua', 10, 2)->nullable(false)->change();
            $table->decimal('energia', 10, 2)->nullable(false)->change();
            $table->decimal('gas', 10, 2)->nullable(false)->change();
            $table->decimal('alimentacao', 10, 2)->nullable(false)->change();
        });

        Schema::table('assinaturas', function (Blueprint $table) {
            $table->date('data_assinatura')->nullable(false)->change();
            $table->string('arquivo_path', 255)->nullable(false)->change();
        });
    }
};
