<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AtualizarUsuarioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $usuarioId = $this->route('id');

        return [
            'nome' => ['sometimes', 'string', 'max:255'],
            'cpf' => ['sometimes', 'string', 'size:11', Rule::unique('usuarios', 'cpf')->ignore($usuarioId)],
            'email' => ['sometimes', 'string', 'email', 'max:255', Rule::unique('usuarios', 'email')->ignore($usuarioId)],
            'data_nascimento' => ['sometimes', 'date', 'before:today'],
            'nivel_id' => ['sometimes', 'integer', 'exists:niveis,id'],
            'senha' => ['sometimes', 'string', 'min:8'],
        ];
    }
}