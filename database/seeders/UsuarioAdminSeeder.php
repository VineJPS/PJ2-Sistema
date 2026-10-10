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
        // Garante que os níveis existem na tabela 'niveis'
        $nivelAdmin = Nivel::firstOrCreate(
            ['nome' => 'Administrador'],
            ['descricao' => 'Acesso total ao sistema']
        );

        $nivelColaborador = Nivel::firstOrCreate(
            ['nome' => 'Colaborador'],
            ['descricao' => 'Acesso básico ao painel']
        );

        // Cria o usuário administrador inicial
        Usuario::updateOrCreate(
            ['email' => 'vinicius107sousa@gmail.com'],
            [
                'nome' => 'Admin',
                'cpf' => '00000000000',
                'data_nascimento' => '2000-01-01',
                'FK_id_nivel' => $nivelAdmin->id,
                'role' => 'administrador',
                'status' => 'ativo',
                'senha' => Hash::make('lordizin'),
            ]
        );

        // Usuários de exemplo para visualização no painel
        $usuariosExemplo = [
            [
                'nome' => 'Roberta Souza',
                'email' => 'roberta.souza@recantobetel.org',
                'cpf' => '12345678901',
                'role' => 'administrador',
                'status' => 'ativo',
                'FK_id_nivel' => $nivelAdmin->id,
            ],
            [
                'nome' => 'Carlos Mendes',
                'email' => 'carlos.mendes@recantobetel.org',
                'cpf' => '23456789012',
                'role' => 'colaborador',
                'status' => 'ativo',
                'FK_id_nivel' => $nivelColaborador->id,
            ],
            [
                'nome' => 'Juliana Prado',
                'email' => 'juliana.prado@recantobetel.org',
                'cpf' => '34567890123',
                'role' => 'colaborador',
                'status' => 'pendente',
                'FK_id_nivel' => $nivelColaborador->id,
            ],
            [
                'nome' => 'Fernanda Lima',
                'email' => 'fernanda.lima@recantobetel.org',
                'cpf' => '45678901234',
                'role' => 'colaborador',
                'status' => 'inativo',
                'FK_id_nivel' => $nivelColaborador->id,
            ],
            [
                'nome' => 'Marcus Souza',
                'email' => 'marcus.souza@recantobetel.org',
                'cpf' => '56789012345',
                'role' => 'administrador',
                'status' => 'ativo',
                'FK_id_nivel' => $nivelAdmin->id,
            ],
        ];

        foreach ($usuariosExemplo as $user) {
            Usuario::updateOrCreate(
                ['email' => $user['email']],
                array_merge($user, [
                    'senha' => Hash::make('senha123'),
                ])
            );
        }
    }
}