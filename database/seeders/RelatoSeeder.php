<?php

namespace Database\Seeders;

use App\Models\Crianca;
use App\Models\Relato;
use App\Models\Usuario;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class RelatoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $usuario = Usuario::first();
        $funcionarioNome = $usuario?->nome ?? 'Marcus Souza';
        $usuarioId = $usuario?->id;

        $criancas = Crianca::take(5)->get();

        $crianca1 = $criancas->get(0);
        $crianca2 = $criancas->get(1);
        $crianca3 = $criancas->get(2);

        $relatos = [
            [
                'nome_crianca' => $crianca1?->nome ?? 'Mary Sousa',
                'nome_funcionario' => $funcionarioNome,
                'FK_id_usuario' => $usuarioId,
                'relato' => 'A criança participou muito bem da atividade em grupo hoje pela manhã. Mostrou-se colaborativa, comunicativa e interessada durante a realização dos trabalhos pedagógicos. Teve excelente interação com os demais colegas de turma.',
                'data_ocorrido' => Carbon::now()->subDays(2)->format('Y-m-d'),
            ],
            [
                'nome_crianca' => $crianca2?->nome ?? 'Ruan Santos',
                'nome_funcionario' => 'Maria Silva',
                'FK_id_usuario' => $usuarioId,
                'relato' => 'Durante o recreio, a criança relatou leve desconforto de dor de cabeça após as atividades ao ar livre. Foi encaminhada para descanso e hidratação na enfermaria. Melhora constatada em seguida.',
                'data_ocorrido' => Carbon::now()->subDays(5)->format('Y-m-d'),
            ],
            [
                'nome_crianca' => $crianca3?->nome ?? 'Flavia Souza',
                'nome_funcionario' => $funcionarioNome,
                'FK_id_usuario' => $usuarioId,
                'relato' => 'Excelente desempenho nas atividades de leitura e artes plásticas. Apresentou grande criatividade e auxiliou seus colegas na organização dos materiais ao final da aula.',
                'data_ocorrido' => Carbon::now()->subDays(8)->format('Y-m-d'),
            ],
        ];

        foreach ($relatos as $dados) {
            Relato::create($dados);
        }
    }
}
