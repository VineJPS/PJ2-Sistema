<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AnamnesePathSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('anamnese_paths')->insert([
            [
                'id' => 1,
                'ficha_anamnese_id' => 1,
                'problemas_cardiacos' => false,
                'problemas_pulmonares' => false,
                'problemas_gastrointestinais' => false,
                'problemas_neurologicos' => false,
                'problemas_ortopedicos' => false,
                'problemas_alergicos' => true,
                'manchas' => 'Nenhuma mancha relevante.',
                'observacoes' => 'Criança sem restrições alimentares severas.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
