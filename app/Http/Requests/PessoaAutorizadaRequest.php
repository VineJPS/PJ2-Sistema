<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\NormalizaTelefone;
use Illuminate\Foundation\Http\FormRequest;

class PessoaAutorizadaRequest extends FormRequest
{
    use NormalizaTelefone;

    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->normalizarTelefone();
    }

    public function rules(): array
    {
        return [
            'nome' => ['required', 'string', 'max:150'],
            'parentesco' => ['required', 'string', 'max:100'],
            'telefone' => ['required', 'regex:' . self::TELEFONE_REGEX],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'Preencha este campo.',
            'telefone.regex' => self::TELEFONE_MENSAGEM,
        ];
    }
}
