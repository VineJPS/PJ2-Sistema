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
            'nome' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('usuarios', 'email')->ignore($usuarioId)],
            'telefone' => ['nullable', 'string', 'max:25'],
            'cpf' => ['nullable', 'string', 'max:20', Rule::unique('usuarios', 'cpf')->ignore($usuarioId)],
            'data_nascimento' => ['nullable', 'date', 'before:today'],
            'role' => ['nullable', 'string', 'in:colaborador,administrador'],
            'status' => ['nullable', 'string', 'in:ativo,pendente,inativo'],
            'nivel_id' => ['nullable', 'integer', 'exists:niveis,id'],
            'senha' => ['nullable', 'string', 'min:6'],
        ];
    }

    public function messages(): array
    {
        return [
            'nome.required' => 'O campo nome é obrigatório.',
            'email.required' => 'O campo e-mail é obrigatório.',
            'email.email' => 'Informe um e-mail válido.',
            'email.unique' => 'Este e-mail já está em uso por outro usuário.',
            'cpf.unique' => 'Este CPF já está cadastrado no sistema.',
            'role.in' => 'A função selecionada é inválida.',
            'status.in' => 'O status selecionado é inválido.',
            'senha.min' => 'A senha deve conter no mínimo 6 caracteres.',
        ];
    }
}