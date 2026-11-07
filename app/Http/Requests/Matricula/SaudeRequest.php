<?php

namespace App\Http\Requests\Matricula;

use Illuminate\Foundation\Http\FormRequest;

class SaudeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'data_preenchimento'      => ['nullable', 'date', 'before_or_equal:today'],
            'queixa_principal'        => ['nullable', 'string', 'max:2000'],
            'medico_assistente'       => ['nullable', 'string', 'max:200'],
            'habitos'                 => ['nullable', 'string', 'max:2000'],
            'antecedentes_familiares' => ['nullable', 'string', 'max:2000'],
            'outras_informacoes'      => ['nullable', 'string', 'max:2000'],

            // Patologias (checkboxes)
            'patologias'                         => ['nullable', 'array'],
            'patologias.febre_reumatica'         => ['nullable', 'boolean'],
            'patologias.problemas_cardiacos'     => ['nullable', 'boolean'],
            'patologias.problemas_renais'        => ['nullable', 'boolean'],
            'patologias.problemas_gastricos'     => ['nullable', 'boolean'],
            'patologias.problemas_respiratorios' => ['nullable', 'boolean'],
            'patologias.problemas_alergicos'     => ['nullable', 'boolean'],
            'patologias.problemas_articulares'   => ['nullable', 'boolean'],
            'patologias.diabetes'                => ['nullable', 'boolean'],
            'patologias.hipertensao'             => ['nullable', 'boolean'],

            // Histórico clínico – booleanos e descrições
            'teve_doenca'           => ['nullable', 'boolean'],
            'quais_doencas'         => ['nullable', 'string', 'max:1000'],
            'em_tratamento_medico'  => ['nullable', 'boolean'],
            'qual_tratamento'       => ['nullable', 'string', 'max:1000'],
            'esta_gravida'          => ['nullable', 'boolean'],
            'usa_medicacao'         => ['nullable', 'boolean'],
            'quais_medicacoes'      => ['nullable', 'string', 'max:1000'],
            'tem_alergia'           => ['nullable', 'boolean'],
            'quais_alergias'        => ['nullable', 'string', 'max:1000'],
            'ja_foi_operado'        => ['nullable', 'boolean'],
            'quais_cirurgias'       => ['nullable', 'string', 'max:1000'],
            'problema_cicatrizacao' => ['nullable', 'boolean'],
            'alerta_anestesia'      => ['nullable', 'boolean'],
            'hemorragia'            => ['nullable', 'boolean'],

            // Declaração e assinatura
            'declaracao_aceita'     => ['required', 'accepted'],
            'assinatura'            => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:7168'],
        ];
    }

    public function messages(): array
    {
        return [
            'declaracao_aceita.required'         => 'É necessário confirmar a veracidade das informações.',
            'declaracao_aceita.accepted'         => 'É necessário confirmar a veracidade das informações.',
            'assinatura.image'                  => 'Envie uma imagem PNG ou JPG.',
            'assinatura.mimes'                  => 'Envie uma imagem PNG ou JPG.',
            'assinatura.max'                    => 'A assinatura deve ter no máximo 7 MB.',
            'data_preenchimento.date'            => 'Informe uma data válida.',
            'data_preenchimento.before_or_equal' => 'A data não pode ser futura.',
        ];
    }
}
