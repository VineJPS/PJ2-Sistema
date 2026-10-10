<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GastoSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('gastos')->insert([
            [
                'endereco_id' => 1,
                'agua' => 85.50,
                'energia' => 150.00,
                'alimentacao' => 600.00,
                'gas' => 110.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'endereco_id' => 2,
                'agua' => 60.00,
                'energia' => 120.00,
                'alimentacao' => 500.00,
                'gas' => 110.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
