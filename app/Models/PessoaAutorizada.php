<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PessoaAutorizada extends Model
{
    use HasFactory;

    protected $table = 'pessoas_autorizadas';

    protected $fillable = [
        'autorizacao_id',
        'nome',
        'parentesco',
        'telefone',
    ];

    public function autorizacao(): BelongsTo
    {
        return $this->belongsTo(Autorizacao::class, 'autorizacao_id');
    }
}
