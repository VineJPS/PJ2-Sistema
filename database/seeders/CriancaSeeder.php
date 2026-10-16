<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CriancaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('criancas')->insert([
            [
                'id' => 1,
                'endereco_id' => 1,
                'nome' => 'Pedrinho da Silva',
                'cpf' => '111.222.333-44',
                'rg' => '55.444.333-2',
                'data_nascimento' => '2022-04-10',
                'escola' => 'Nenhuma',
                'relatos' => 'Criança calma e sociável.',
                'conselho_tutelar' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'endereco_id' => 2,
                'nome' => 'Ana Clara de Souza',
                'cpf' => '222.333.444-55',
                'rg' => '66.555.444-3',
                'data_nascimento' => '2021-08-15',
                'escola' => 'EMEI Pequeno Passo',
                'relatos' => 'Apresenta desenvolvimento motor adequado.',
                'conselho_tutelar' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
