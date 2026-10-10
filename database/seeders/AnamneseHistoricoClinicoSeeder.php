<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AnamneseHistoricoClinicoSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('anamnese_historico_clinicos')->insert([
            [
                'id' => 1,
                'ficha_anamnese_id' => 1,
                'teve_doenca' => true,
                'quais_doencas' => 'Varicela aos 1 ano de idade',
                'em_tratamento_medico' => false,
                'qual_tratamento' => null,
                'esta_gravida' => false,
                'usa_medicacao' => false,
                'quais_medicacoes' => null,
                'tem_alergia' => true,
                'quais_alergias' => 'Alergia a picada de pernilongo',
                'ja_foi_operado' => false,
                'quais_cirurgias' => null,
                'problema_visao' => false,
                'problema_audicao' => false,
                'problema_fala' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
