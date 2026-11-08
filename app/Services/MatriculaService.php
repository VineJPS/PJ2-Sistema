<?php

namespace App\Services;

use App\Models\AnamneseHistoricoClinico;
use App\Models\AnamnesePath;
use App\Models\Assinatura;
use App\Models\Autorizacao;
use App\Models\Crianca;
use App\Models\Endereco;
use App\Models\FichaAnamnese;
use App\Models\Gasto;
use App\Models\Responsavel;
use Illuminate\Support\Facades\DB;

class MatriculaService
{
    /**
     * Grava no banco todos os dados coletados nas etapas da matrícula.
     * A criança fica Ativa se nada estiver pendente; caso contrário, Pendente.
     */
    public function registrar(array $crianca, array $responsaveis, array $endereco, array $saude, array $autorizacoes, array $pendencias): Crianca
    {
        return DB::transaction(function () use ($crianca, $responsaveis, $endereco, $saude, $autorizacoes, $pendencias) {
            $enderecoModel = Endereco::create([
                'estado' => $endereco['estado'],
                'cidade' => $endereco['cidade'],
                'bairro' => $endereco['bairro'],
                'rua' => $endereco['rua'],
                'numero' => $endereco['numero'],
                'ponto_referencia' => $endereco['ponto_referencia'] ?? null,
                'moradia' => $endereco['tipo_moradia'],
            ]);

            Gasto::create([
                'endereco_id' => $enderecoModel->id,
                'valor_aluguel' => $this->moeda($endereco['valor_aluguel'] ?? null),
                'agua' => $this->moeda($endereco['agua'] ?? null),
                'energia' => $this->moeda($endereco['energia'] ?? null),
                'alimentacao' => $this->moeda($endereco['alimentacao'] ?? null),
                'gas' => $this->moeda($endereco['gas'] ?? null),
            ]);

            $criancaModel = Crianca::create([
                'endereco_id' => $enderecoModel->id,
                'nome' => $crianca['nome'],
                'foto_path' => $crianca['foto_path'] ?? null,
                'cpf' => $crianca['cpf'] ?? null,
                'rg' => $crianca['rg'] ?? null,
                'data_nascimento' => $crianca['data_nascimento'],
                'escola' => $crianca['escola'] ?? null,
                'periodo' => $crianca['periodo'] ?? null,
                'serie' => $crianca['serie'] ?? null,
                'turma_betel' => $crianca['turma_betel'] ?? null,
                'status' => $pendencias ? 'Pendente' : 'Ativo',
                'conselho_tutelar' => false,
            ]);

            foreach ($responsaveis['responsaveis'] as $i => $r) {
                $responsavel = Responsavel::create([
                    'endereco_id' => $enderecoModel->id,
                    'nome' => $r['nome'],
                    'telefone' => $r['telefone'],
                    'profissao' => $r['profissao'] ?? null,
                    'endereco_comercial' => $r['endereco_comercial'] ?? null,
                    'religiao' => $r['religiao'] ?? null,
                    'grau_escolaridade' => $r['escolaridade'] ?? null,
                    'salario' => $this->moeda($r['salario'] ?? null),
                    'nis' => $i === 0 ? ($responsaveis['beneficio_nis'] ?? null) : null,
                ]);
                $criancaModel->responsaveis()->attach($responsavel->id, ['grau_parentesco' => $r['parentesco']]);
            }

            foreach ($responsaveis['membros'] ?? [] as $m) {
                $criancaModel->membrosFamiliares()->create([
                    'nome' => $m['nome'],
                    'parentesco' => $m['parentesco'],
                    'data_nascimento' => $m['data_nascimento'] ?? null,
                    'renda' => $this->moeda($m['renda'] ?? null),
                ]);
            }

            $criancaModel->rendaFamiliar()->create([
                'renda_total' => $this->moeda($responsaveis['renda_total'] ?? null) ?? 0,
                'renda_per_capita' => $this->moeda($responsaveis['renda_per_capita'] ?? null) ?? 0,
                'recebe_beneficio' => (bool) $responsaveis['recebe_beneficio'],
                'beneficio_nis' => $responsaveis['beneficio_nis'] ?? null,
                'beneficio_tipo' => $responsaveis['beneficio_tipo'] ?? null,
                'beneficio_valor' => $this->moeda($responsaveis['beneficio_valor'] ?? null),
            ]);

            if (!empty($saude)) {
                $fichaAnamnese = FichaAnamnese::create([
                    'crianca_id' => $criancaModel->id,
                    'queixa_principal' => $saude['queixa_principal'] ?? null,
                    'motivo_atendimento' => 'Matrícula',
                    'habitos' => $saude['habitos'] ?? null,
                    'antecedentes_familiares' => $saude['antecedentes_familiares'] ?? null,
                    'outras_informacoes' => $saude['outras_informacoes'] ?? null,
                    'medico_assistente' => $saude['medico_assistente'] ?? null,
                    'medico_telefone' => $saude['medico_telefone'] ?? null,
                    'data_preenchimento' => $saude['data_preenchimento'] ?? now()->toDateString(),
                    'declaracao_aceita' => (bool) ($saude['declaracao_aceita'] ?? false),
                    'assinatura_path' => $saude['assinatura_path'] ?? null,
                ]);

                AnamneseHistoricoClinico::create([
                    'ficha_anamnese_id' => $fichaAnamnese->id,
                    'teve_doenca' => (bool) ($saude['teve_doenca'] ?? false),
                    'quais_doencas' => !empty($saude['teve_doenca']) ? ($saude['quais_doencas'] ?? null) : null,
                    'em_tratamento_medico' => (bool) ($saude['em_tratamento_medico'] ?? false),
                    'qual_tratamento' => !empty($saude['em_tratamento_medico']) ? ($saude['qual_tratamento'] ?? null) : null,
                    'esta_gravida' => (bool) ($saude['esta_gravida'] ?? false),
                    'usa_medicacao' => (bool) ($saude['usa_medicacao'] ?? false),
                    'quais_medicacoes' => !empty($saude['usa_medicacao']) ? ($saude['quais_medicacoes'] ?? null) : null,
                    'tem_alergia' => (bool) ($saude['tem_alergia'] ?? false),
                    'quais_alergias' => !empty($saude['tem_alergia']) ? ($saude['quais_alergias'] ?? null) : null,
                    'instrucao_alergia' => $saude['instrucao_alergia'] ?? null,
                    'ja_foi_operado' => (bool) ($saude['ja_foi_operado'] ?? false),
                    'quais_cirurgias' => !empty($saude['ja_foi_operado']) ? ($saude['quais_cirurgias'] ?? null) : null,
                    'problema_visao' => false,
                    'problema_audicao' => false,
                    'problema_fala' => false,
                ]);

                $patologias = $saude['patologias'] ?? [];
                $doencasEspecificas = [
                    ['nome' => 'Febre reumática',      'selecionado' => (bool) ($patologias['febre_reumatica'] ?? false)],
                    ['nome' => 'Cardíaco',             'selecionado' => (bool) ($patologias['problemas_cardiacos'] ?? false)],
                    ['nome' => 'Renal',                'selecionado' => (bool) ($patologias['problemas_renais'] ?? false)],
                    ['nome' => 'Gástrico',             'selecionado' => (bool) ($patologias['problemas_gastricos'] ?? false)],
                    ['nome' => 'Respiratório',         'selecionado' => (bool) ($patologias['problemas_respiratorios'] ?? false)],
                    ['nome' => 'Articular/Reumatismo', 'selecionado' => (bool) ($patologias['problemas_articulares'] ?? false)],
                    ['nome' => 'Diabetes',             'selecionado' => (bool) ($patologias['diabetes'] ?? false)],
                    ['nome' => 'Hipertensão',          'selecionado' => (bool) ($patologias['hipertensao'] ?? false)],
                ];

                AnamnesePath::create([
                    'ficha_anamnese_id' => $fichaAnamnese->id,
                    'problemas_cardiacos' => (bool) ($patologias['problemas_cardiacos'] ?? false),
                    'problemas_pulmonares' => (bool) ($patologias['problemas_respiratorios'] ?? false),
                    'problemas_gastrointestinais' => (bool) ($patologias['problemas_gastricos'] ?? false),
                    'problemas_alergicos' => (bool) ($patologias['problemas_alergicos'] ?? false),
                    'problema_cicatrizacao' => (bool) ($saude['problema_cicatrizacao'] ?? false),
                    'alerta_anestesia' => (bool) ($saude['alerta_anestesia'] ?? false),
                    'hemorragia' => (bool) ($saude['hemorragia'] ?? false),
                    'doencas_especificas' => $doencasEspecificas,
                ]);
            }

            $autorizacao = Autorizacao::create([
                'crianca_id' => $criancaModel->id,
                'autoriza_frequencia' => (bool) $autorizacoes['autoriza_frequencia'],
                'sai_sozinho' => (bool) $autorizacoes['sai_sozinho'],
            ]);
            foreach ($autorizacoes['autorizados'] ?? [] as $p) {
                $autorizacao->pessoasAutorizadas()->create([
                    'nome' => $p['nome'],
                    'parentesco' => $p['parentesco'],
                    'telefone' => $p['telefone'],
                ]);
            }

            Assinatura::create([
                'crianca_id' => $criancaModel->id,
                'termo_aceito' => (bool) $autorizacoes['termo_aceito'],
                'data_assinatura' => $autorizacoes['data_assinatura'] ?? null,
                'arquivo_path' => $autorizacoes['assinatura_path'] ?? null,
            ]);

            return $criancaModel;
        });
    }

    private function moeda(?string $valor): ?float
    {
        if (blank($valor)) {
            return null;
        }

        $numeros = preg_replace('/[^\d,]/', '', $valor);

        return (float) str_replace(',', '.', $numeros);
    }
}
