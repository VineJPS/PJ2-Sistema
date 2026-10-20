<?php

namespace App\Models;

use Database\Seeders\CriancaDataSeeder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Crianca extends Model
{
    use HasFactory;

    protected $table = 'criancas';

    protected $fillable = [
        'endereco_id',
        'nome',
        'foto_path',
        'cpf',
        'rg',
        'data_nascimento',
        'escola',
        'periodo',
        'serie',
        'turma_betel',
        'status',
        'relatos',
        'conselho_tutelar',
    ];

    protected function casts(): array
    {
        return [
            'data_nascimento' => 'date',
            'conselho_tutelar' => 'boolean',
        ];
    }

    public function endereco(): BelongsTo
    {
        return $this->belongsTo(Endereco::class, 'endereco_id');
    }

    public function responsaveis(): BelongsToMany
    {
        return $this->belongsToMany(Responsavel::class, 'crianca_responsavel', 'crianca_id', 'responsavel_id')
            ->withPivot('grau_parentesco');
    }

    public function membrosFamiliares(): HasMany
    {
        return $this->hasMany(MembroFamiliar::class, 'crianca_id');
    }

    public function rendaFamiliar(): HasOne
    {
        return $this->hasOne(RendaFamiliar::class, 'crianca_id');
    }

    public function autorizacao(): HasOne
    {
        return $this->hasOne(Autorizacao::class, 'crianca_id');
    }

    public function assinatura(): HasOne
    {
        return $this->hasOne(Assinatura::class, 'crianca_id');
    }

    /** Dados resumidos para os cards da home. */
    public static function listaTela(): array
    {
        return static::orderBy('nome')->get()->map(fn (self $c) => [
            'id' => $c->id,
            'nome' => $c->nome,
            'idade' => $c->idade(),
            'status' => $c->status,
        ])->all();
    }

    /** Dados completos no formato esperado pelas telas de seção da criança. */
    public static function dadosTela($id): ?array
    {
        $crianca = static::with(['endereco', 'responsaveis', 'autorizacao.pessoasAutorizadas', 'assinatura'])->find($id);

        return $crianca?->paraTela();
    }

    public function idade(): int
    {
        return $this->data_nascimento ? $this->data_nascimento->age : 0;
    }

    public function paraTela(): array
    {
        $endereco = $this->endereco;
        $enderecoTexto = $endereco
            ? "{$endereco->rua}, {$endereco->numero} - {$endereco->bairro}, {$endereco->cidade}/{$endereco->estado}"
            : '';
        $autorizacao = $this->autorizacao;
        $assinatura = $this->assinatura;

        // Seções ainda sem tabela própria (saúde, relatos) seguem os dados de exemplo do seeder
        $padrao = CriancaDataSeeder::getDadosPadrao();

        return array_merge($padrao, [
            'id' => $this->id,
            'nome' => $this->nome,
            'idade' => $this->idade(),
            'status' => $this->status,
            'dataEntrada' => $this->created_at?->format('d/m/Y'),
            'dataNascimento' => $this->data_nascimento?->format('d/m/Y') ?? '',
            'cpf' => $this->cpf ?? '',
            'rg' => $this->rg ?? '',
            'endereco' => $enderecoTexto,
            'enderecoCampos' => [
                'rua' => $endereco->rua ?? '',
                'numero' => $endereco->numero ?? '',
                'bairro' => $endereco->bairro ?? '',
                'cidade' => $endereco->cidade ?? '',
                'estado' => $endereco->estado ?? '',
            ],
            'escola' => $this->escola ?? '',
            'periodo' => $this->periodo ?? '',
            'serie' => $this->serie ?? '',
            'escolaBetel' => 'Recanto Betel',
            'periodoBetel' => $this->periodo ?? '',
            'classeBetel' => $this->turma_betel ?? '',
            'responsaveis' => $this->responsaveis->map(fn (Responsavel $r) => [
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
                    'nome' => $this->responsaveis->first()->nome ?? '--',
                    'dataAssinatura' => $assinatura?->data_assinatura?->format('d/m/Y') ?? '--',
                    'arquivo' => $assinatura?->arquivo_path,
                ],
            ],
        ]);
    }
}
