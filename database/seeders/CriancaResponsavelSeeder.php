<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CriancaResponsavelSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('crianca_responsavel')->insert([
            [
                'crianca_id' => 1,
                'responsavel_id' => 1,
                'grau_parentesco' => 'Mãe',
            ],
            [
                'crianca_id' => 2,
                'responsavel_id' => 2,
                'grau_parentesco' => 'Pai',
            ],
        ]);
    }
}
