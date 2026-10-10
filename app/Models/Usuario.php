<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Usuario extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'usuarios';

    protected $fillable = [
        'nome',
        'cpf',
        'email',
        'telefone',
        'data_nascimento',
        'FK_id_nivel',
        'nivel_id',
        'role',
        'status',
        'senha',
    ];

    protected $hidden = [
        'senha',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'data_nascimento' => 'date',
            'senha' => 'hashed',
        ];
    }

    public function getAuthPasswordName(): string
    {
        return 'senha';
    }

    public function nivel(): BelongsTo
    {
        return $this->belongsTo(Nivel::class, 'FK_id_nivel');
    }

    public function getNameAttribute(): ?string
    {
        return $this->nome ?? ($this->email ? explode('@', $this->email)[0] : 'Usuário');
    }

    public function getPhoneAttribute(): ?string
    {
        return $this->attributes['phone'] ?? $this->attributes['telefone'] ?? null;
    }

    public function getTelefoneAttribute(): ?string
    {
        return $this->attributes['telefone'] ?? $this->attributes['phone'] ?? null;
    }

    public function getRoleAttribute(): string
    {
        return $this->attributes['role'] ?? ($this->nivel && strtolower($this->nivel->nome) === 'administrador' ? 'administrador' : 'colaborador');
    }

    public function getStatusAttribute(): string
    {
        return $this->attributes['status'] ?? 'ativo';
    }
}