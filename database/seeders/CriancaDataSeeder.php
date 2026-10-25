<?php

namespace Database\Seeders;

use App\Models\Assinatura;
use App\Models\Autorizacao;
use App\Models\Crianca;
use App\Models\Endereco;
use App\Models\Gasto;
use App\Models\Responsavel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CriancaDataSeeder extends Seeder
{
    /**
     * Dados padrão utilizados nas telas de todas as crianças.
     */
    public static function getDadosPadrao(): array
    {
        return [

            // DADOS PESSOAIS
            'dataNascimento' => '20/05/2010',
            'cpf' => '123.456.789-10',
            'rg' => '12.345.678-9',
            'endereco' => 'Rua das Acácias, 247 - Jardim Primavera, Tatuí - SP, 18270-000',
            'escola' => 'EM Vila Paulina',
            'periodo' => 'Vespertino',
            'serie' => '5º ano',

            // MATRÍCULA BETEL (exibida somente quando status = Inativo)
            'escolaBetel' => 'Recanto Betel',
            'periodoBetel' => 'Vespertino',
            'classeBetel' => 'Turma 3',

            // SAÚDE
            'saude' => [
                'tem_alergia_grave' => true,
                'alerta_remedio' => 'Alergia a dipirona',
                'alerta_remedio_instrucao' => 'Não administrar em nenhuma hipótese!',
                'queixa_principal' => 'Falta de ar em atividades físicas intensas, uso controlado de bombinha.',
                'sofre_doenca' => 'Asma e diabete tipo 1',
                'em_tratamento' => 'Sim',
                'gravidez' => 'Sim',
                'uso_medicacao' => 'Insulina, conforme prescrição',
                'medico_assistente' => 'Dr. Renato Alves',
                'medico_telefone' => '(15) 99254-5936',

                'cirurgias' => [
                    'Apendicite (2020)',
                    'Amigdalectomia (2023)',
                ],

                'doencas_especificas' => [
                    [
                        'nome' => 'Febre reumática',
                        'selecionado' => false,
                    ],
                    [
                        'nome' => 'Cardíaco',
                        'selecionado' => true,
                    ],
                    [
                        'nome' => 'Renal',
                        'selecionado' => false,
                    ],
                    [
                        'nome' => 'Gástrico',
                        'selecionado' => false,
                    ],
                    [
                        'nome' => 'Respiratório',
                        'selecionado' => true,
                    ],
                    [
                        'nome' => 'Articular/Reumatismo',
                        'selecionado' => false,
                    ],
                    [
                        'nome' => 'Diabetes',
                        'selecionado' => true,
                    ],
                    [
                        'nome' => 'Hipertensão',
                        'selecionado' => false,
                    ],
                ],

                'problema_cicatrizacao' => 'Não',
                'alerta_anestesia' => 'Sim',
                'hemorragia' => 'Não',
                'habitos' => 'Precisa medir glicemia antes do lanche da tarde',
                'antecedentes_familiares' => 'Pai com histórico de diabetes tipo 1',
                'outras_informacoes' => 'Nenhuma informação adicional.',
            ],

            // RESPONSÁVEIS
            'responsaveis' => [
                [
                    'id' => 1,
                    'parentesco' => 'Mãe',
                    'nome' => 'Roberta Souza',
                    'telefone' => '(15) 99123-4560',
                    'enderecoResidencial' => 'Rua Antônio Bento, 245 - Vila Paulina, Tatuí/SP',
                    'enderecoComercial' => 'Rua Maria Betânia, 456 - Centro, Tatuí/SP',
                ],
                [
                    'id' => 2,
                    'parentesco' => 'Pai',
                    'nome' => 'Carlos Souza',
                    'telefone' => '(15) 99456-8207',
                    'enderecoResidencial' => 'Rua Antônio Bento, 245 - Vila Paulina, Tatuí/SP',
                    'enderecoComercial' => 'Rua Maria Betânia, 456 - Centro, Tatuí/SP',
                ],
            ],

            // AUTORIZAÇÕES
            'autorizacoes' => [
                'autorizacao_permissoes' => [
                    [
                        'id' => 1,
                        'titulo' => 'Autorização de saída',
                        'descricao' => 'Autoriza a criança a sair da instituição sem acompanhamento dos responsáveis.',
                        'autorizado' => true,
                    ],
                    [
                        'id' => 2,
                        'titulo' => 'Atendimento odontológico',
                        'descricao' => 'Autoriza procedimentos odontológicos de rotina realizados pela instituição.',
                        'autorizado' => false,
                    ],
                ],

                'autorizacao_pessoas_buscar' => [
                    [
                        'id' => 1,
                        'parentesco' => 'Mãe',
                        'nome' => 'Roberta Souza',
                        'telefone' => '(15) 99123-4560',
                    ],
                    [
                        'id' => 2,
                        'parentesco' => 'Pai',
                        'nome' => 'Carlos Souza',
                        'telefone' => '(15) 99456-8207',
                    ],
                ],

                'autorizacao_assinatura' => [
                    'nome' => 'Roberta Souza',
                    'dataAssinatura' => '12/06/2020',
                ],
            ],

            // RELATOS
            'relatos' => [
                'relatos_lista' => [
                    [
                        'relato_id' => 1,
                        'relato_funcionario' => 'MARIA SILVA',
                        'relato_data' => '10/07/2026',
                        'relato_texto_curto' => 'Ana participou bem da atividade em grupo hoje pela manhã. Ela se mostro...',
                        'relato_texto_completo' => 'Ana participou da atividade em grupo hoje pela manhã. Se mostrou colaborativa, comunicativa e interessada durante a realização de nossa atividade. Demonstrou facilidade em interagir com os colegas, contribuindo com ideias e respeitando as opiniões dos demais. Mostrou atenção às orientações e empenho para concluir a proposta de forma satisfatória.',
                    ],
                    [
                        'relato_id' => 2,
                        'relato_funcionario' => 'JOSÉ CARVALHO',
                        'relato_data' => '08/07/2026',
                        'relato_texto_curto' => 'Ana participou bem da atividade em grupo hoje pela manhã. Ela se mostro...',
                        'relato_texto_completo' => 'Durante a atividade da tarde, observou-se excelente interação com a equipe pedagógica e boa resolução de problemas.',
                    ],
                    [
                        'relato_id' => 3,
                        'relato_funcionario' => 'LUISA SANTANA',
                        'relato_data' => '05/07/2026',
                        'relato_texto_curto' => 'Ana participou bem da atividade em grupo hoje pela manhã. Ela se mostro...',
                        'relato_texto_completo' => 'Atendimento focado em desenvolvimento de habilidades socioemocionais. A criança manteve o foco na tarefa do início ao fim.',
                    ],
                ],
            ],

            // CADASTRO
            'cadastro' => [
                'dataNascimento' => '20/05/2010',
                'cpf' => '123.456.789-10',
                'rg' => '12.345.678-9',
                'endereco' => 'Rua das Acácias, 247 - Jardim Primavera, Tatuí - SP, 18270-000',
                'escola' => 'EM Vila Paulina',
                'periodo' => 'Vespertino',
                'serie' => '5º ano',
            ],
        ];
    }

    private static function getCriancasBasicas(): array
    {
        return [
            [
                'id' => 1,
                'nome' => 'Mary Sousa',
                'idade' => 8,
                'status' => 'Pendente',
            ],
            [
                'id' => 2,
                'nome' => 'Ruan Santos',
                'idade' => 4,
                'status' => 'Ativo',
            ],
            [
                'id' => 3,
                'nome' => 'Flavia Souza',
                'idade' => 8,
                'status' => 'Inativo',
            ],
            [
                'id' => 4,
                'nome' => 'Ruan Santos',
                'idade' => 6,
                'status' => 'Ativo',
            ],
            [
                'id' => 5,
                'nome' => 'Bela Ferreira',
                'idade' => 5,
                'status' => 'Ativo',
            ],
            [
                'id' => 6,
                'nome' => 'Pedro Antonio',
                'idade' => 10,
                'status' => 'Ativo',
            ],
            [
                'id' => 7,
                'nome' => 'Michelle Soares',     
                'idade' => 6,
                'status' => 'Ativo',
            ],
            [
                'id' => 8,
                'nome' => 'Julia Oliveira',
                'idade' => 5,
                'status' => 'Ativo',
            ],
            [
                'id' => 9,
                'nome' => 'Kaique Lima',
                'idade' => 8,
                'status' => 'Pendente',
            ],
        ];
    }

    private static function getCriancasData(): array
    {
        $criancasBasicas = self::getCriancasBasicas();
        $dadosPadrao = self::getDadosPadrao();

        return array_map(function ($crianca) use ($dadosPadrao) {
            return array_merge($dadosPadrao, $crianca);
        }, $criancasBasicas);
    }

    public function run(): void
    {
        $padrao = self::getDadosPadrao();

        foreach (self::getCriancasBasicas() as $basica) {
            DB::transaction(function () use ($basica, $padrao) {
                $endereco = Endereco::create([
                    'rua' => 'Rua das Acácias',
                    'numero' => '247',
                    'bairro' => 'Jardim Primavera',
                    'cidade' => 'Tatuí',
                    'estado' => 'SP',
                    'ponto_referencia' => 'Próximo à praça central',
                    'moradia' => 'Própria',
                ]);

                Gasto::create([
                    'endereco_id' => $endereco->id,
                    'agua' => 85.50,
                    'energia' => 150.00,
                    'alimentacao' => 600.00,
                    'gas' => 110.00,
                ]);

                $crianca = Crianca::create([
                    'endereco_id' => $endereco->id,
                    'nome' => $basica['nome'],
                    'rg' => $padrao['rg'],
                    'data_nascimento' => now()->subYears($basica['idade'])->subMonths(2)->toDateString(),
                    'escola' => $padrao['escola'],
                    'periodo' => $padrao['periodo'],
                    'serie' => $padrao['serie'],
                    'turma_betel' => $padrao['classeBetel'],
                    'status' => $basica['status'],
                    'conselho_tutelar' => false,
                ]);

                // CPF é único: gera um distinto por criança
                $crianca->update(['cpf' => sprintf('%03d.456.789-%02d', 100 + $crianca->id, $crianca->id)]);

                foreach ($padrao['responsaveis'] as $r) {
                    $responsavel = Responsavel::create([
                        'endereco_id' => $endereco->id,
                        'nome' => $r['nome'],
                        'telefone' => $r['telefone'],
                        'endereco_comercial' => $r['enderecoComercial'],
                    ]);
                    $crianca->responsaveis()->attach($responsavel->id, ['grau_parentesco' => $r['parentesco']]);
                }

                $autorizacao = Autorizacao::create([
                    'crianca_id' => $crianca->id,
                    'autoriza_frequencia' => true,
                    'sai_sozinho' => false,
                ]);
                foreach ($padrao['autorizacoes']['autorizacao_pessoas_buscar'] as $p) {
                    $autorizacao->pessoasAutorizadas()->create([
                        'nome' => $p['nome'],
                        'parentesco' => $p['parentesco'],
                        'telefone' => $p['telefone'],
                    ]);
                }

                Assinatura::create([
                    'crianca_id' => $crianca->id,
                    'termo_aceito' => true,
                    'data_assinatura' => now()->subYear()->toDateString(),
                ]);
            });
        }
    }

    public static function getCriancaById($id): ?array
    {
        foreach (self::getCriancasData() as $crianca) {
            if ($crianca['id'] == $id) {
                return $crianca;
            }
        }

        return null;
    }

    public static function getTodosCriancas(): array
    {
        return self::getCriancasData();
    }
}