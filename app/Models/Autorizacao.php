<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Autorizacao extends Model
{
    use HasFactory;

    protected $table = 'autorizacoes';

    protected $fillable = [
        'crianca_id',
        'autoriza_frequencia',
        'sai_sozinho',
    ];

    protected function casts(): array
    {
        return [
            'autoriza_frequencia' => 'boolean',
            'sai_sozinho' => 'boolean',
        ];
    }

    public function crianca(): BelongsTo
    {
        return $this->belongsTo(Crianca::class, 'crianca_id');
    }

    public function pessoasAutorizadas(): HasMany
    {
        return $this->hasMany(PessoaAutorizada::class, 'autorizacao_id');
    }
}
