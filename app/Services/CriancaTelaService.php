<?php

namespace App\Services;

use App\Models\Crianca;
use App\Models\MembroFamiliar;
use App\Models\PessoaAutorizada;
use App\Models\Responsavel;

class CriancaTelaService
{
    /** Dados resumidos para os cards da home. */
    public function lista(): array
    {
        return Crianca::orderBy('nome')->get()->map(fn (Crianca $c) => [
            'id' => $c->id,
            'nome' => $c->nome,
            'idade' => $c->idade(),
            'status' => $c->status,
            'foto' => $c->fotoUrl(),
        ])->all();
    }

    /** Dados completos no formato esperado pelas telas de seção da criança. */
    public function dados($id): ?array
    {
        $crianca = Crianca::with(['endereco', 'responsaveis', 'autorizacao.pessoasAutorizadas', 'assinatura'])->find($id);

        return $crianca ? $this->formatar($crianca) : null;
    }

    // Dados do ambiente familiar: complementos dos responsáveis, composição, renda, endereço e gastos
    public function ambiente(Crianca $crianca): array
    {
        $endereco = $crianca->endereco;
        $gasto = $endereco?->gasto;
        $renda = $crianca->rendaFamiliar;

        return [
            'responsaveis' => $crianca->responsaveis->map(fn (Responsavel $r) => [
                'id' => $r->id,
                'parentesco' => $r->pivot->grau_parentesco,
                'nome' => $r->nome,
                'profissao' => $r->profissao ?? '',
                'religiao' => $r->religiao ?? '',
                'escolaridade' => $r->grau_escolaridade ?? '',
                'salario' => $this->dinheiro($r->salario),
            ])->values()->all(),
            'membros' => $crianca->membrosFamiliares->map(fn (MembroFamiliar $m) => [
                'nome' => $m->nome,
                'parentesco' => $m->parentesco,
                'dataNascimento' => $m->data_nascimento?->format('Y-m-d') ?? '',
                'renda' => $this->dinheiro($m->renda),
            ])->values()->all(),
            'renda' => [
                'total' => $this->dinheiro($renda?->renda_total),
                'perCapita' => $this->dinheiro($renda?->renda_per_capita),
                'recebeBeneficio' => (bool) ($renda?->recebe_beneficio ?? false),
                'nis' => $renda?->beneficio_nis ?? '',
                'tipo' => $renda?->beneficio_tipo ?? '',
                'valor' => $this->dinheiro($renda?->beneficio_valor),
            ],
            'endereco' => [
                'estado' => $endereco?->estado ?? '',
                'cidade' => $endereco?->cidade ?? '',
                'bairro' => $endereco?->bairro ?? '',
                'rua' => $endereco?->rua ?? '',
                'numero' => $endereco?->numero ?? '',
                'pontoReferencia' => $endereco?->ponto_referencia ?? '',
                'moradia' => $endereco?->moradia ?? '',
                'valorAluguel' => $this->dinheiro($gasto?->valor_aluguel),
            ],
            'gastos' => [
                'agua' => $this->dinheiro($gasto?->agua),
                'energia' => $this->dinheiro($gasto?->energia),
                'gas' => $this->dinheiro($gasto?->gas),
                'alimentacao' => $this->dinheiro($gasto?->alimentacao),
            ],
        ];
    }

    private function formatar(Crianca $crianca): array
    {
        $endereco = $crianca->endereco;
        $enderecoTexto = $endereco
            ? "{$endereco->rua}, {$endereco->numero} - {$endereco->bairro}, {$endereco->cidade}/{$endereco->estado}"
            : '';
        $autorizacao = $crianca->autorizacao;
        $assinatura = $crianca->assinatura;

        return [
            'id' => $crianca->id,
            'nome' => $crianca->nome,
            'idade' => $crianca->idade(),
            'status' => $crianca->status,
            'foto' => $crianca->fotoUrl(),
            'dataEntrada' => $crianca->created_at?->format('d/m/Y'),
            'dataNascimento' => $crianca->data_nascimento?->format('d/m/Y') ?? '',
            'cpf' => $crianca->cpf ?? '',
            'rg' => $crianca->rg ?? '',
            'endereco' => $enderecoTexto,
            'enderecoCampos' => [
                'rua' => $endereco->rua ?? '',
                'numero' => $endereco->numero ?? '',
                'bairro' => $endereco->bairro ?? '',
                'cidade' => $endereco->cidade ?? '',
                'estado' => $endereco->estado ?? '',
            ],
            'escola' => $crianca->escola ?? '',
            'periodo' => $crianca->periodo ?? '',
            'serie' => $crianca->serie ?? '',
            'escolaBetel' => 'Recanto Betel',
            'periodoBetel' => $crianca->periodo ?? '',
            'classeBetel' => $crianca->turma_betel ?? '',
            'ambiente' => $this->ambiente($crianca),
            'responsaveis' => $crianca->responsaveis->map(fn (Responsavel $r) => [
                'id' => $r->id,
                'parentesco' => $r->pivot->grau_parentesco,
                'nome' => $r->nome,
                'telefone' => $r->telefone,
                'enderecoResidencial' => $enderecoTexto,
                'enderecoComercial' => $r->endereco_comercial ?? '',
            ])->values()->all(),
            'autorizacoes' => [
                'autorizacao_permissoes' => [
                    [
                        'id' => 'autoriza_frequencia',
                        'titulo' => 'Autorização de frequência',
                        'descricao' => 'Autoriza a criança a frequentar as atividades da instituição.',
                        'autorizado' => (bool) ($autorizacao->autoriza_frequencia ?? false),
                    ],
                    [
                        'id' => 'sai_sozinho',
                        'titulo' => 'Autorização de saída',
                        'descricao' => 'Autoriza a criança a sair da instituição sem acompanhamento dos responsáveis.',
                        'autorizado' => (bool) ($autorizacao->sai_sozinho ?? false),
                    ],
                ],
                'autorizacao_pessoas_buscar' => $autorizacao
                    ? $autorizacao->pessoasAutorizadas->map(fn (PessoaAutorizada $p) => [
                        'id' => $p->id,
                        'parentesco' => $p->parentesco,
                        'nome' => $p->nome,
                        'telefone' => $p->telefone,
                    ])->values()->all()
                    : [],
                'autorizacao_assinatura' => [
                    'nome' => $crianca->responsaveis->first()->nome ?? '--',
                    'dataAssinatura' => $assinatura?->data_assinatura?->format('d/m/Y') ?? '--',
                    'arquivo' => $assinatura?->arquivo_path,
                ],
            ],
            'relatos' => [
                'relatos_lista' => $crianca->relatos()->orderBy('data_ocorrido', 'desc')->get()->map(fn ($r) => [
                    'relato_id' => $r->id,
                    'relato_funcionario' => $r->nome_funcionario,
                    'relato_data' => $r->data_ocorrido?->format('d/m/Y') ?? '',
                    'relato_texto_curto' => \Illuminate\Support\Str::limit($r->relato, 70),
                    'relato_texto_completo' => $r->relato,
                ])->all(),
            ],
        ];
    }

    private function dinheiro($valor): string
    {
        return $valor === null || $valor === '' ? '' : number_format((float) $valor, 2, ',', '.');
    }
}
