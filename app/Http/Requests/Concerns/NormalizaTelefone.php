<?php

namespace App\Http\Requests\Concerns;

trait NormalizaTelefone
{
    // (DD) 9XXXX-XXXX para celulares ou (DD) XXXX-XXXX para fixos
    public const TELEFONE_REGEX = '/^\([1-9]\d\) (?:9\d{4}|[2-5]\d{3})-\d{4}$/';

    public const TELEFONE_MENSAGEM = 'Informe um telefone válido, ex: (15) 91234-5678.';

    // Mantém só os dígitos e reaplica a máscara
    protected function formatarTelefone(mixed $valor): mixed
    {
        if (!is_string($valor)) {
            return $valor;
        }

        $d = ltrim(preg_replace('/\D/', '', $valor), '0');
        if (strlen($d) > 11 && str_starts_with($d, '55')) {
            $d = substr($d, 2);
        }

        return match (strlen($d)) {
            11 => sprintf('(%s) %s-%s', substr($d, 0, 2), substr($d, 2, 5), substr($d, 7)),
            10 => sprintf('(%s) %s-%s', substr($d, 0, 2), substr($d, 2, 4), substr($d, 6)),
            default => $valor,
        };
    }

    protected function normalizarTelefone(): void
    {
        if ($this->has('telefone')) {
            $this->merge(['telefone' => $this->formatarTelefone($this->input('telefone'))]);
        }
    }

    // Normaliza o telefone de cada item de uma lista aninhada
    protected function normalizarTelefonesDaLista(string $lista): void
    {
        $itens = $this->input($lista);
        if (!is_array($itens)) {
            return;
        }

        foreach ($itens as $i => $item) {
            if (is_array($item) && array_key_exists('telefone', $item)) {
                $itens[$i]['telefone'] = $this->formatarTelefone($item['telefone']);
            }
        }
        $this->merge([$lista => $itens]);
    }
}
