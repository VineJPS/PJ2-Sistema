<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class FichaAnamnese extends Model
{
    protected $table = 'fichas_anamnese';

    protected $fillable = [
        'crianca_id',
        'queixa_principal',
        'motivo_atendimento',
        'habitos',
        'antecedentes_familiares',
        'outras_informacoes',
        'medico_assistente',
        'medico_telefone',
        'data_preenchimento',
    ];

    protected function casts(): array
    {
        return [
            'data_preenchimento' => 'date',
        ];
    }

    public function crianca(): BelongsTo
    {
        return $this->belongsTo(Crianca::class, 'crianca_id');
    }

    public function historicoClinico(): HasOne
    {
        return $this->hasOne(AnamneseHistoricoClinico::class, 'ficha_anamnese_id');
    }

    public function paths(): HasOne
    {
        return $this->hasOne(AnamnesePath::class, 'ficha_anamnese_id');
    }
}
