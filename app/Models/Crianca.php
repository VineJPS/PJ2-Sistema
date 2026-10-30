<?php

namespace App\Models;

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

    public function relatos(): HasMany
    {
        return $this->hasMany(Relato::class, 'nome_crianca', 'nome');
    }

    /** A foto é servida por rota autenticada, sem depender do link simbólico do storage. */
    public function fotoUrl(): ?string
    {
        if (blank($this->foto_path)) {
            return null;
        }

        return route('crianca.foto', $this->id) . '?v=' . ($this->updated_at?->timestamp ?? 0);
    }

    public function idade(): int
    {
        return $this->data_nascimento ? $this->data_nascimento->age : 0;
    }
}
