<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UsuarioAdminSeeder::class,
            EnderecoSeeder::class,
            GastoSeeder::class,
            ResponsavelSeeder::class,
            CriancaSeeder::class,
            CriancaResponsavelSeeder::class,
            FichaAnamneseSeeder::class,
            AnamneseHistoricoClinicoSeeder::class,
            AnamnesePathSeeder::class,
        ]);
    }
}