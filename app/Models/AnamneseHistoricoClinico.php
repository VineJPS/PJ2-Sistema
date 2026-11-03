<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnamneseHistoricoClinico extends Model
{
    protected $table = 'anamnese_historico_clinicos';

    protected $fillable = [
        'ficha_anamnese_id',
        'teve_doenca',
        'quais_doencas',
        'em_tratamento_medico',
        'qual_tratamento',
        'esta_gravida',
        'usa_medicacao',
        'quais_medicacoes',
        'tem_alergia',
        'quais_alergias',
        'instrucao_alergia',
        'ja_foi_operado',
        'quais_cirurgias',
        'problema_visao',
        'problema_audicao',
        'problema_fala',
    ];

    protected function casts(): array
    {
        return [
            'teve_doenca'         => 'boolean',
            'em_tratamento_medico'=> 'boolean',
            'esta_gravida'        => 'boolean',
            'usa_medicacao'       => 'boolean',
            'tem_alergia'         => 'boolean',
            'ja_foi_operado'      => 'boolean',
            'problema_visao'      => 'boolean',
            'problema_audicao'    => 'boolean',
            'problema_fala'       => 'boolean',
        ];
    }

    public function fichaAnamnese(): BelongsTo
    {
        return $this->belongsTo(FichaAnamnese::class, 'ficha_anamnese_id');
    }
}
