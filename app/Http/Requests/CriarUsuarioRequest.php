<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CriarUsuarioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nome' => ['required', 'string', 'max:255'],
            'cpf' => ['required', 'string', 'size:11', 'unique:usuarios,cpf'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:usuarios,email'],
            'data_nascimento' => ['required', 'date', 'before:today'],
            'nivel_id' => ['required', 'integer', 'exists:niveis,id'],
            'senha' => ['required', 'string', 'min:8'],
        ];
    }
}