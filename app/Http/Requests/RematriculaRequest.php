<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RematriculaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'escola'      => ['required', 'string', 'max:255'],
            'periodo'     => ['required', 'in:Matutino,Vespertino,Integral'],
            'serie'       => ['required', 'string', 'max:50'],
            'turma_betel' => ['required', 'in:Turma 1,Turma 2,Turma 3'],
            'ano_letivo'  => ['required', 'integer', 'min:2000', 'max:2100'],
        ];
    }

    public function messages(): array
    {
        return [
            'required'             => 'Preencha este campo.',
            'periodo.in'           => 'Selecione um período válido.',
            'turma_betel.in'       => 'Selecione uma turma válida.',
            'ano_letivo.integer'   => 'Informe um ano válido.',
            'ano_letivo.min'       => 'Informe um ano a partir de 2000.',
            'ano_letivo.max'       => 'Informe um ano válido.',
        ];
    }
}
