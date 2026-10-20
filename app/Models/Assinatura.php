<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Assinatura extends Model
{
    use HasFactory;

    protected $table = 'assinaturas';

    protected $fillable = [
        'crianca_id',
        'termo_aceito',
        'data_assinatura',
        'arquivo_path',
    ];

    protected function casts(): array
    {
        return [
            'termo_aceito' => 'boolean',
            'data_assinatura' => 'date',
        ];
    }

    public function crianca(): BelongsTo
    {
        return $this->belongsTo(Crianca::class, 'crianca_id');
    }
}
