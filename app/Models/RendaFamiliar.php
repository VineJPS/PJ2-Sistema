<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RendaFamiliar extends Model
{
    use HasFactory;

    protected $table = 'rendas_familiares';

    protected $fillable = [
        'crianca_id',
        'renda_total',
        'renda_per_capita',
        'recebe_beneficio',
        'beneficio_nis',
        'beneficio_tipo',
        'beneficio_valor',
    ];

    protected function casts(): array
    {
        return [
            'renda_total' => 'decimal:2',
            'renda_per_capita' => 'decimal:2',
            'recebe_beneficio' => 'boolean',
            'beneficio_valor' => 'decimal:2',
        ];
    }

    public function crianca(): BelongsTo
    {
        return $this->belongsTo(Crianca::class, 'crianca_id');
    }
}
