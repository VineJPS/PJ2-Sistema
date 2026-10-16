<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ResponsavelSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('responsaveis')->insert([
            [
                'id' => 1,
                'endereco_id' => 1,
                'nome' => 'Maria da Silva',
                'telefone' => '(11) 98765-4321',
                'profissao' => 'Do Lar',
                'grau_escolaridade' => 'Ensino Médio Completo',
                'salario' => 1320.00,
                'nis' => '123.45678.90-1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'endereco_id' => 2,
                'nome' => 'João de Souza',
                'telefone' => '(11) 91234-5678',
                'profissao' => 'Pedreiro',
                'grau_escolaridade' => 'Ensino Fundamental Incompleto',
                'salario' => 2200.00,
                'nis' => '987.65432.10-9',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
