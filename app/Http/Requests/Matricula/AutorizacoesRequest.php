<?php

namespace App\Http\Requests\Matricula;

use App\Http\Requests\Concerns\NormalizaTelefone;
use Illuminate\Foundation\Http\FormRequest;

class AutorizacoesRequest extends FormRequest
{
    use NormalizaTelefone;

    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'autoriza_frequencia' => $this->boolean('autoriza_frequencia'),
            'sai_sozinho' => $this->boolean('sai_sozinho'),
            'termo_aceito' => $this->boolean('termo_aceito'),
        ]);

        $this->normalizarTelefonesDaLista('autorizados');
    }

    public function rules(): array
    {
        return [
            'autoriza_frequencia' => ['boolean'],
            'sai_sozinho' => ['boolean'],
            'autorizados' => ['array'],
            'autorizados.*.nome' => ['required', 'string', 'max:255'],
            'autorizados.*.parentesco' => ['required', 'string', 'max:100'],
            'autorizados.*.telefone' => ['required', 'regex:' . self::TELEFONE_REGEX],
            'termo_aceito' => ['accepted'],
            'data_assinatura' => ['nullable', 'date', 'before_or_equal:today'],
            'assinatura' => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:5120'],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'Preencha este campo.',
            'termo_aceito.accepted' => 'É necessário aceitar o termo de compromisso.',
            'data_assinatura.date' => 'Informe uma data válida.',
            'data_assinatura.before_or_equal' => 'A data não pode ser futura.',
            'assinatura.required' => 'Envie a assinatura do responsável.',
            'assinatura.image' => 'Envie uma imagem PNG ou JPG.',
            'assinatura.mimes' => 'Envie uma imagem PNG ou JPG.',
            'assinatura.max' => 'A assinatura deve ter no máximo 5MB.',
            'autorizados.*.telefone.regex' => self::TELEFONE_MENSAGEM,
        ];
    }
}
