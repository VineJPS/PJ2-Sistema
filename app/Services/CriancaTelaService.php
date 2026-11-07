<?php

namespace App\Services;

use App\Models\Crianca;
use App\Models\FichaAnamnese;
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

    /** Dados de saúde da criança lidos do banco. */
    public function saude($id): array
    {
        $crianca = Crianca::find($id);
        if (! $crianca) {
            return [];
        }

        $ficha = $crianca->fichaAnamnese()->with(['historicoClinico', 'paths'])->first();
        $hc = $ficha?->historicoClinico;
        $paths = $ficha?->paths;

        $patologiasNomes = [
            'Febre reumática',
            'Cardíaco',
            'Renal',
            'Gástrico',
            'Respiratório',
            'Articular/Reumatismo',
            'Diabetes',
            'Hipertensão',
        ];

        $doencasSalvas = $paths?->doencas_especificas;
        if (is_array($doencasSalvas) && count($doencasSalvas) > 0) {
            $mapa = [];
            foreach ($doencasSalvas as $d) {
                $nomeNorm = self::normalizarNomePatologia($d['nome'] ?? '');
                $mapa[$nomeNorm] = (bool) ($d['selecionado'] ?? false);
            }
            $doencasEspecificas = array_map(fn ($nome) => [
                'nome' => $nome,
                'selecionado' => $mapa[$nome] ?? false,
            ], $patologiasNomes);
        } elseif ($paths) {
            $doencasEspecificas = [
                ['nome' => 'Febre reumática',      'selecionado' => false],
                ['nome' => 'Cardíaco',             'selecionado' => (bool) $paths->problemas_cardiacos],
                ['nome' => 'Renal',                'selecionado' => false],
                ['nome' => 'Gástrico',             'selecionado' => (bool) $paths->problemas_gastrointestinais],
                ['nome' => 'Respiratório',         'selecionado' => (bool) $paths->problemas_pulmonares],
                ['nome' => 'Articular/Reumatismo', 'selecionado' => false],
                ['nome' => 'Diabetes',             'selecionado' => false],
                ['nome' => 'Hipertensão',          'selecionado' => false],
            ];
        } else {
            $doencasEspecificas = array_map(fn ($nome) => [
                'nome' => $nome,
                'selecionado' => false,
            ], $patologiasNomes);
        }

        // Cirurgias: separa por quebra de linha ou vírgula
        $cirurgias = [];
        if ($hc?->quais_cirurgias) {
            $cirurgias = array_values(array_filter(
                array_map('trim', preg_split('/[\n,]+/', $hc->quais_cirurgias))
            ));
        }

        return [
            // Banner de Alerta Crítico
            'alerta_remedio'          => $hc?->tem_alergia ? ($hc->quais_alergias ?? '') : '',
            'alerta_remedio_instrucao'=> $hc?->instrucao_alergia ?? '',

            // Queixa principal / evolução
            'queixa_principal'        => $ficha?->queixa_principal ?? '',

            // Questionário de saúde
            'sofre_doenca'            => $hc?->quais_doencas ?? ($hc?->teve_doenca ? 'Sim' : ''),
            'em_tratamento'           => $hc?->em_tratamento_medico ? 'Sim' : 'Não',
            'gravidez'                => $hc?->esta_gravida ? 'Sim' : 'Não',
            'uso_medicacao'           => $hc?->quais_medicacoes ?? ($hc?->usa_medicacao ? 'Sim' : ''),
            'medico_assistente'       => $ficha?->medico_assistente ?? '',
            'medico_telefone'         => $ficha?->medico_telefone ?? '',

            // Cirurgias
            'cirurgias'               => $cirurgias,

            // Doenças específicas (chips)
            'doencas_especificas'     => $doencasEspecificas,

            // Cicatrização, anestesia e hemorragia
            'problema_cicatrizacao'   => $paths?->problema_cicatrizacao ? 'Sim' : 'Não',
            'alerta_anestesia'        => $paths?->alerta_anestesia ? 'Sim' : 'Não',
            'hemorragia'              => $paths?->hemorragia ? 'Sim' : 'Não',

            // Hábitos
            'habitos'                 => $ficha?->habitos ?? '',

            // Antecedentes familiares
            'antecedentes_familiares' => $ficha?->antecedentes_familiares ?? '',

            // Outras informações
            'outras_informacoes'      => $ficha?->outras_informacoes ?? '',
        ];
    }

    private static function normalizarNomePatologia(string $nome): string
    {
        $map = [
            'febre reumática' => 'Febre reumática',
            'cardíaco' => 'Cardíaco',
            'cardiaco' => 'Cardíaco',
            'problemas cardíacos' => 'Cardíaco',
            'problema cardíacos' => 'Cardíaco',
            'renal' => 'Renal',
            'problemas renais' => 'Renal',
            'gástrico' => 'Gástrico',
            'gastrico' => 'Gástrico',
            'problemas gástricos' => 'Gástrico',
            'problema gástricos' => 'Gástrico',
            'respiratório' => 'Respiratório',
            'respiratorio' => 'Respiratório',
            'problemas respiratórios' => 'Respiratório',
            'articular/reumatismo' => 'Articular/Reumatismo',
            'problemas articulares' => 'Articular/Reumatismo',
            'diabetes' => 'Diabetes',
            'hipertensão' => 'Hipertensão',
            'hipertensao' => 'Hipertensão',
        ];
        $lower = mb_strtolower(trim($nome));
        return $map[$lower] ?? $nome;
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
