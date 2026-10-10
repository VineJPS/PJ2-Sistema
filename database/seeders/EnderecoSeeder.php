<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EnderecoSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('enderecos')->insert([
            [
                'id' => 1,
                'estado' => 'SP',
                'cidade' => 'São Paulo',
                'bairro' => 'Jardim Primavera',
                'rua' => 'Rua das Flores',
                'numero' => '123',
                'ponto_referencia' => 'Próximo à praça central',
                'moradia' => 'Alugada',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'estado' => 'SP',
                'cidade' => 'São Paulo',
                'bairro' => 'Vila Esperança',
                'rua' => 'Avenida Brasil',
                'numero' => '456',
                'ponto_referencia' => 'Em frente ao mercado',
                'moradia' => 'Própria',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
