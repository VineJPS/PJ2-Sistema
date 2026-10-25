<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Endereco extends Model
{
    use HasFactory;

    protected $table = 'enderecos';

    protected $fillable = [
        'estado',
        'cidade',
        'bairro',
        'rua',
        'numero',
        'ponto_referencia',
        'moradia',
    ];

    public function gasto(): HasOne
    {
        return $this->hasOne(Gasto::class, 'endereco_id');
    }

    public function criancas(): HasMany
    {
        return $this->hasMany(Crianca::class, 'endereco_id');
    }

    public function responsaveis(): HasMany
    {
        return $this->hasMany(Responsavel::class, 'endereco_id');
    }
}
