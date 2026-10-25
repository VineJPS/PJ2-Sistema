<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Gasto extends Model
{
    use HasFactory;

    protected $table = 'gastos';

    protected $fillable = [
        'endereco_id',
        'valor_aluguel',
        'agua',
        'energia',
        'alimentacao',
        'gas',
    ];

    protected function casts(): array
    {
        return [
            'valor_aluguel' => 'decimal:2',
            'agua' => 'decimal:2',
            'energia' => 'decimal:2',
            'alimentacao' => 'decimal:2',
            'gas' => 'decimal:2',
        ];
    }

    public function endereco(): BelongsTo
    {
        return $this->belongsTo(Endereco::class, 'endereco_id');
    }
}
