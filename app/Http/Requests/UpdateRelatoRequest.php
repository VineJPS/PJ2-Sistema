<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRelatoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nome_funcionario' => ['required', 'string', 'max:255'],
            'nome_crianca' => ['required', 'string', 'max:255'],
            'data_ocorrido' => ['required', 'date'],
            'relato' => ['required', 'string', 'max:1000'],
            'FK_id_usuario' => ['nullable', 'integer', 'exists:usuarios,id'],
        ];
    }

    /**
     * Mensagens de validação personalizadas em português.
     */
    public function messages(): array
    {
        return [
            'nome_funcionario.required' => 'O campo nome do funcionário é obrigatório.',
            'nome_funcionario.string' => 'O nome do funcionário deve ser um texto válido.',
            'nome_funcionario.max' => 'O nome do funcionário não pode exceder 255 caracteres.',
            'nome_crianca.required' => 'O campo nome da criança é obrigatório.',
            'nome_crianca.string' => 'O nome da criança deve ser um texto válido.',
            'nome_crianca.max' => 'O nome da criança não pode exceder 255 caracteres.',
            'data_ocorrido.required' => 'A data do ocorrido é obrigatória.',
            'data_ocorrido.date' => 'Informe uma data válida para o ocorrido.',
            'relato.required' => 'A descrição do relato é obrigatória.',
            'relato.string' => 'A descrição do relato deve ser um texto válido.',
            'relato.max' => 'A descrição do relato não pode ter mais de 1000 caracteres.',
            'FK_id_usuario.exists' => 'O usuário informado não existe.',
        ];
    }
}
