<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FichaAnamneseSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('fichas_anamnese')->insert([
            [
                'id' => 1,
                'crianca_id' => 1,
                'queixa_principal' => 'Dificuldades leves para dormir à noite.',
                'motivo_atendimento' => 'Vaga em período integral na creche.',
                'habitos' => 'Dorme cedo, usa chupeta.',
                'antecedentes_familiares' => 'Sem histórico de doenças crônicas graves.',
                'outras_informacoes' => 'Vacinada em dia.',
                'data_preenchimento' => '2026-02-10',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
