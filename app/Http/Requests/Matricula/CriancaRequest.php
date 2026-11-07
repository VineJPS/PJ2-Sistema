<?php

namespace App\Http\Requests\Matricula;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CriancaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'foto' => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:5120'],
            'nome' => ['required', 'string', 'max:255'],
            'data_nascimento' => ['required', 'date', 'before_or_equal:today'],
            'cpf' => ['nullable', 'regex:/^\d{3}\.\d{3}\.\d{3}-\d{2}$/', Rule::unique('criancas', 'cpf')],
            'rg' => ['nullable', 'string', 'max:20'],
            'escola' => ['required', 'string', 'max:255'],
            'periodo' => ['required', 'in:Matutino,Vespertino,Integral'],
            'serie' => ['required', 'string', 'max:50'],
            'turma_betel' => ['required', 'in:Turma 1,Turma 2,Turma 3'],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'Preencha este campo.',
            'foto.image' => 'Envie uma imagem PNG ou JPG.',
            'foto.mimes' => 'Envie uma imagem PNG ou JPG.',
            'foto.max' => 'A foto deve ter no máximo 5MB.',
            'data_nascimento.date' => 'Informe uma data válida.',
            'data_nascimento.before_or_equal' => 'A data não pode ser futura.',
            'cpf.regex' => 'Informe o CPF no formato 000.000.000-00.',
            'cpf.unique' => 'Já existe uma criança cadastrada com este CPF.',
            'periodo.in' => 'Selecione um período válido.',
            'turma_betel.in' => 'Selecione uma turma válida.',
        ];
    }
}
