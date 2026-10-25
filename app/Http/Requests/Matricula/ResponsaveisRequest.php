<?php

namespace App\Http\Requests\Matricula;

use App\Http\Requests\Concerns\NormalizaTelefone;
use Illuminate\Foundation\Http\FormRequest;

class ResponsaveisRequest extends FormRequest
{
    use NormalizaTelefone;

    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        // Linhas de composição familiar totalmente vazias são descartadas antes de validar
        $this->merge([
            'membros' => collect($this->input('membros', []))
                ->filter(fn ($membro) => collect($membro)->filter(fn ($v) => filled($v))->isNotEmpty())
                ->values()
                ->all(),
        ]);

        $this->normalizarTelefonesDaLista('responsaveis');
    }

    public function rules(): array
    {
        return [
            'responsaveis' => ['required', 'array', 'min:1'],
            'responsaveis.*.nome' => ['required', 'string', 'max:255'],
            'responsaveis.*.parentesco' => ['required', 'string', 'max:100'],
            'responsaveis.*.telefone' => ['required', 'regex:' . self::TELEFONE_REGEX],
            'responsaveis.*.escolaridade' => ['nullable', 'string', 'max:100'],
            'responsaveis.*.salario' => ['nullable', 'string', 'max:20'],
            'responsaveis.*.profissao' => ['nullable', 'string', 'max:100'],
            'responsaveis.*.endereco_comercial' => ['required', 'string', 'max:255'],
            'responsaveis.*.religiao' => ['nullable', 'string', 'max:100'],
            'membros' => ['nullable', 'array'],
            'membros.*.nome' => ['required', 'string', 'max:255'],
            'membros.*.parentesco' => ['required', 'string', 'max:100'],
            'membros.*.data_nascimento' => ['nullable', 'date', 'before_or_equal:today'],
            'membros.*.renda' => ['nullable', 'string', 'max:20'],
            'renda_total' => ['required', 'string', 'max:20'],
            'renda_per_capita' => ['required', 'string', 'max:20'],
            'recebe_beneficio' => ['required', 'boolean'],
            'beneficio_nis' => ['required_if:recebe_beneficio,1', 'nullable', 'regex:/^\d{3}\.\d{5}\.\d{2}-\d$/'],
            'beneficio_tipo' => ['required_if:recebe_beneficio,1', 'nullable', 'in:Bolsa Família,Cadúnico,Outros'],
            'beneficio_valor' => ['required_if:recebe_beneficio,1', 'nullable', 'string', 'max:20'],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'Preencha este campo.',
            'required_if' => 'Preencha este campo.',
            'responsaveis.required' => 'Informe ao menos um responsável.',
            'responsaveis.*.telefone.regex' => self::TELEFONE_MENSAGEM,
            'beneficio_nis.regex' => 'Informe o NIS no formato 000.00000.00-0.',
            '*.data_nascimento.date' => 'Informe uma data válida.',
            '*.data_nascimento.before_or_equal' => 'A data não pode ser futura.',
        ];
    }
}
