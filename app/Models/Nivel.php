<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Nivel extends Model
{
    use HasFactory;

    protected $table = 'niveis';

    protected $fillable = [
        'nome',
        'descricao',
    ];

    public function usuarios(): HasMany
    {
        return $this->hasMany(Usuario::class, 'nivel_id');
    }
}