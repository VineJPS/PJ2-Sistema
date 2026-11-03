<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Relato extends Model
{
    use HasFactory;

    protected $table = 'relatos';

    protected $fillable = [
        'nome_crianca',
        'nome_funcionario',
        'FK_id_usuario',
        'relato',
        'data_ocorrido',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'data_ocorrido' => 'date',
        ];
    }

    /**
     * Relacionamento com o usuário que registrou o relato.
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'FK_id_usuario');
    }
}
