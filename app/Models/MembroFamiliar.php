<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MembroFamiliar extends Model
{
    use HasFactory;

    protected $table = 'membros_familiares';

    protected $fillable = [
        'crianca_id',
        'nome',
        'parentesco',
        'data_nascimento',
        'renda',
    ];

    protected function casts(): array
    {
        return [
            'data_nascimento' => 'date',
            'renda' => 'decimal:2',
        ];
    }

    public function crianca(): BelongsTo
    {
        return $this->belongsTo(Crianca::class, 'crianca_id');
    }
}
