<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UsuarioAdminSeeder::class,
            CriancaDataSeeder::class,
            FichaAnamneseSeeder::class,
            AnamneseHistoricoClinicoSeeder::class,
            AnamnesePathSeeder::class,
            RelatoSeeder::class,
        ]);
    }
}