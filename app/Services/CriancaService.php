<?php

namespace App\Services;

use App\Models\Crianca;
use App\Models\Endereco;
use App\Models\Responsavel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CriancaService
{
    /**
     * Atualiza apenas os dados escolares da criança (rematrícula)
     * e reativa o status para Ativo ao concluir.
     */
    public function rematricula(Crianca $crianca, array $dados): Crianca
    {
        $crianca->update([
            'escola'      => $dados['escola'],
            'periodo'     => $dados['periodo'],
            'serie'       => $dados['serie'],
            'turma_betel' => $dados['turma_betel'],
            'ano_letivo'  => $dados['ano_letivo'],
            'status'      => 'Ativo',
        ]);

        return $crianca->fresh();
    }

    public function excluir(Crianca $crianca): void
    {
        DB::transaction(function () use ($crianca) {
            $crianca->load(['responsaveis', 'assinatura']);
            $enderecoId = $crianca->endereco_id;
            $arquivos = array_filter([$crianca->foto_path, $crianca->assinatura?->arquivo_path]);
            $responsaveisIds = $crianca->responsaveis->pluck('id');

            // As tabelas dependentes caem em cascata junto com a criança
            $crianca->delete();

            // Responsáveis que não estão ligados a outra criança deixam de existir
            Responsavel::whereIn('id', $responsaveisIds)
                ->whereDoesntHave('criancas')
                ->delete();

            // O endereço só é removido quando ninguém mais o utiliza
            $emUso = Crianca::where('endereco_id', $enderecoId)->exists()
                || Responsavel::where('endereco_id', $enderecoId)->exists();
            if (!$emUso) {
                Endereco::whereKey($enderecoId)->delete();
            }

            if ($arquivos) {
                Storage::disk('public')->delete($arquivos);
            }
        });
    }
}
