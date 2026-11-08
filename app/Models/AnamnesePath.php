<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnamnesePath extends Model
{
    protected $table = 'anamnese_paths';

    protected $fillable = [
        'ficha_anamnese_id',
        'problemas_cardiacos',
        'problemas_pulmonares',
        'problemas_gastrointestinais',
        'problemas_neurologicos',
        'problemas_ortopedicos',
        'problemas_alergicos',
        'manchas',
        'observacoes',
        'problema_cicatrizacao',
        'alerta_anestesia',
        'hemorragia',
        'doencas_especificas',
    ];

    protected function casts(): array
    {
        return [
            'problemas_cardiacos'         => 'boolean',
            'problemas_pulmonares'        => 'boolean',
            'problemas_gastrointestinais' => 'boolean',
            'problemas_neurologicos'      => 'boolean',
            'problemas_ortopedicos'       => 'boolean',
            'problemas_alergicos'         => 'boolean',
            'problema_cicatrizacao'       => 'boolean',
            'alerta_anestesia'            => 'boolean',
            'hemorragia'                  => 'boolean',
            'doencas_especificas'         => 'array',
        ];
    }

    public function fichaAnamnese(): BelongsTo
    {
        return $this->belongsTo(FichaAnamnese::class, 'ficha_anamnese_id');
    }
}
