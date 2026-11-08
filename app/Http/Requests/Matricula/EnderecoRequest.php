<?php

namespace App\Http\Requests\Matricula;

use Illuminate\Foundation\Http\FormRequest;

class EnderecoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('estado')) {
            $this->merge([
                'estado' => strtoupper((string) $this->input('estado')),
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'rua' => ['required', 'string', 'max:255'],
            'numero' => ['required', 'string', 'max:20'],
            'bairro' => ['required', 'string', 'max:100'],
            'cidade' => ['required', 'string', 'max:100'],
            'estado' => ['required', 'regex:/^[A-Z]{2}$/'],
            'ponto_referencia' => ['nullable', 'string', 'max:255'],
            'tipo_moradia' => ['required', 'in:Própria,Cedida,Alugada'],
            'valor_aluguel' => ['required_if:tipo_moradia,Alugada', 'nullable', 'string', 'max:20'],
            'agua' => ['nullable', 'string', 'max:20'],
            'energia' => ['nullable', 'string', 'max:20'],
            'alimentacao' => ['nullable', 'string', 'max:20'],
            'gas' => ['nullable', 'string', 'max:20'],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'Preencha este campo.',
            'required_if' => 'Preencha este campo.',
            'estado.regex' => 'Informe a sigla do estado (ex: SP).',
            'tipo_moradia.in' => 'Selecione o tipo de moradia.',
        ];
    }
}
