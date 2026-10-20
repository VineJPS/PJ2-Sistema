<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Responsavel extends Model
{
    use HasFactory;

    protected $table = 'responsaveis';

    protected $fillable = [
        'endereco_id',
        'nome',
        'telefone',
        'profissao',
        'endereco_comercial',
        'religiao',
        'grau_escolaridade',
        'salario',
        'nis',
    ];

    protected function casts(): array
    {
        return [
            'salario' => 'decimal:2',
        ];
    }

    public function endereco(): BelongsTo
    {
        return $this->belongsTo(Endereco::class, 'endereco_id');
    }

    public function criancas(): BelongsToMany
    {
        return $this->belongsToMany(Crianca::class, 'crianca_responsavel', 'responsavel_id', 'crianca_id')
            ->withPivot('grau_parentesco');
    }
}
