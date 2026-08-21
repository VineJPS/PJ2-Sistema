<?php

namespace Database\Seeders;

use App\Models\Nivel;
use App\Models\Usuario;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsuarioAdminSeeder extends Seeder
{
    public function run(): void
    {
        // Garante que o nível de Administrador existe na tabela 'niveis'
        $nivelAdmin = Nivel::firstOrCreate(
            ['nome' => 'Administrador'],
            ['descricao' => 'Acesso total ao sistema']
        );

        // Cria o usuário administrador inicial
        Usuario::updateOrCreate(
            ['email' => 'vinicius107sousa@gmail.com'],
            [
                'nome' => 'Admin',
                'cpf' => '00000000000',
                'data_nascimento' => '2000-01-01',
                'FK_id_nivel' => $nivelAdmin->id,
                'senha' => Hash::make('lordizin'),
            ]
        );
    }
}