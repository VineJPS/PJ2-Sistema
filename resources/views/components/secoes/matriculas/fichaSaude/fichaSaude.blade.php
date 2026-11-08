<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Anamnese (saúde) - Recanto Betel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/cadastroCrianca.css') }}">
    <link rel="stylesheet" href="{{ asset('css/cadastroEnderecoGastos.css') }}">
    <link rel="stylesheet" href="{{ asset('css/cadastroAutorizacoesAssinaturas.css') }}">
    <link rel="stylesheet" href="{{ asset('css/fichaSaude.css') }}">
</head>

@php
    $dados  = $dados ?? [];
    $campo  = fn (string $nome) => old($nome, $dados[$nome] ?? '');
    $bool   = fn (string $nome) => (bool) old($nome, $dados[$nome] ?? false);

    /* Histórico clínico – pares [pergunta, campo_sim, campo_descreva] */
    $historico = [
        ['Sofre de alguma doença?',          'teve_doenca',           'quais_doencas'],
        ['Está em tratamento médico?',        'em_tratamento_medico',  'qual_tratamento'],
        ['Está grávida? (se aplicável)',      'esta_gravida',          null],
        ['Usa alguma medicação?',             'usa_medicacao',         'quais_medicacoes'],
        ['Tem alguma alergia?',               'tem_alergia',           'quais_alergias'],
        ['Já foi operado(a)?',               'ja_foi_operado',        'quais_cirurgias'],
        ['Tem problema de cicatrização?',     'problema_cicatrizacao', null],
        ['Tem problema com anestesia?',       'alerta_anestesia',      null],
        ['Tem problema de hemorragia?',       'hemorragia',            null],
    ];

    /* Patologias – checkboxes */
    $patologias = [
        'febre_reumatica'         => 'Febre reumática',
        'problemas_cardiacos'     => 'Problema cardíacos',
        'problemas_renais'        => 'Problemas renais',
        'problemas_gastricos'     => 'Problema gástricos',
        'problemas_respiratorios' => 'Problemas respiratórios',
        'problemas_alergicos'     => 'Problema alérgicos',
        'problemas_articulares'   => 'Problemas articulares',
        'diabetes'                => 'Diabetes',
        'hipertensao'             => 'Hipertensão',
    ];

    /* Estado inicial Alpine para o histórico clínico */
    $historicoInicial = [];
    foreach ($historico as [$_, $campoSim, $campoDesc]) {
        $historicoInicial[$campoSim] = (bool) old($campoSim, $dados[$campoSim] ?? false);
    }

    $assinaturaSalva = !empty($dados['assinatura_path'] ?? null);
@endphp

<body class="cadastro-body">
    <div class="cadastro-layout">
        <x-sidebar />

        <main class="cadastro-main">
            <div class="cadastro-wrap-saude" x-data="fichaSaudeApp(@js($historicoInicial))">

                <header class="cadastro-heading">
                    <h1>Anamnese (saúde)</h1>
                    <p>Histórico de saúde, patologias e informações clínicas.</p>
                </header>

                @if (session('success'))
                    <div class="aviso-sucesso" role="status">{{ session('success') }}</div>
                @endif

                @if ($errors->any())
                    <div class="aviso-erro" role="alert">
                        <strong>Revise os campos abaixo:</strong>
                        <ul>
                            @foreach (array_unique($errors->all()) as $erro)
                                <li>{{ $erro }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form
                    class="cadastro-form"
                    method="POST"
                    action="{{ route('matricula.saude.salvar') }}"
                    enctype="multipart/form-data"
                    aria-label="Ficha de saúde"
                >
                    @csrf

                    {{-- ════════════════════════════════════════
                         SEÇÃO 1 · Ficha de anamnese
                    ════════════════════════════════════════ --}}
                    <section class="cadastro-card secao-card">
                        <div class="secao-header">
                            <span class="secao-numero" aria-hidden="true">1</span>
                            <div>
                                <h2 class="secao-titulo">Ficha de anamnese</h2>
                                <p class="secao-subtitulo">Informações gerais de saúde.</p>
                            </div>
                        </div>

                        <div class="cadastro-fields">
                            {{-- Data de preenchimento --}}
                            <div class="field field-half">
                                <label for="data_preenchimento">Data de preenchimento</label>
                                <input
                                    id="data_preenchimento"
                                    name="data_preenchimento"
                                    type="date"
                                    max="{{ now()->toDateString() }}"
                                    value="{{ $campo('data_preenchimento') }}"
                                    @error('data_preenchimento') aria-invalid="true" @enderror
                                >
                                @error('data_preenchimento')<small class="field-error">{{ $message }}</small>@enderror
                            </div>

                            {{-- Médico assistente --}}
                            <div class="field field-half">
                                <label for="medico_assistente">Médico assistente</label>
                                <input
                                    id="medico_assistente"
                                    name="medico_assistente"
                                    type="text"
                                    placeholder="Nome do médico"
                                    maxlength="200"
                                    value="{{ $campo('medico_assistente') }}"
                                    @error('medico_assistente') aria-invalid="true" @enderror
                                >
                                @error('medico_assistente')<small class="field-error">{{ $message }}</small>@enderror
                            </div>

                            {{-- Queixa principal --}}
                            <div class="field field-full">
                                <label for="queixa_principal">Queixa principal</label>
                                <textarea
                                    id="queixa_principal"
                                    name="queixa_principal"
                                    class="saude-textarea"
                                    placeholder="Motivo principal de atenção, se houver."
                                    maxlength="2000"
                                    @error('queixa_principal') aria-invalid="true" @enderror
                                >{{ $campo('queixa_principal') }}</textarea>
                                @error('queixa_principal')<small class="field-error">{{ $message }}</small>@enderror
                            </div>

                            {{-- Hábitos --}}
                            <div class="field field-full">
                                <label for="habitos">Hábitos</label>
                                <textarea
                                    id="habitos"
                                    name="habitos"
                                    class="saude-textarea"
                                    placeholder="Sono, alimentação, rotina…"
                                    maxlength="2000"
                                    @error('habitos') aria-invalid="true" @enderror
                                >{{ $campo('habitos') }}</textarea>
                                @error('habitos')<small class="field-error">{{ $message }}</small>@enderror
                            </div>

                            {{-- Antecedentes familiares --}}
                            <div class="field field-full">
                                <label for="antecedentes_familiares">Antecedentes familiares</label>
                                <textarea
                                    id="antecedentes_familiares"
                                    name="antecedentes_familiares"
                                    class="saude-textarea"
                                    placeholder="Doenças na família"
                                    maxlength="2000"
                                    @error('antecedentes_familiares') aria-invalid="true" @enderror
                                >{{ $campo('antecedentes_familiares') }}</textarea>
                                @error('antecedentes_familiares')<small class="field-error">{{ $message }}</small>@enderror
                            </div>

                            {{-- Outras informações --}}
                            <div class="field field-full">
                                <label for="outras_informacoes">Outras informações</label>
                                <textarea
                                    id="outras_informacoes"
                                    name="outras_informacoes"
                                    class="saude-textarea"
                                    placeholder="Qualquer outra informação relevante"
                                    maxlength="2000"
                                    @error('outras_informacoes') aria-invalid="true" @enderror
                                >{{ $campo('outras_informacoes') }}</textarea>
                                @error('outras_informacoes')<small class="field-error">{{ $message }}</small>@enderror
                            </div>
                        </div>
                    </section>

                    {{-- ════════════════════════════════════════
                         SEÇÃO 2 · Patologias
                    ════════════════════════════════════════ --}}
                    <section class="cadastro-card secao-card">
                        <div class="secao-header">
                            <span class="secao-numero" aria-hidden="true">2</span>
                            <div>
                                <h2 class="secao-titulo">Patologias</h2>
                                <p class="secao-subtitulo">Marque o que se aplica.</p>
                            </div>
                        </div>

                        <div class="patologias-grid">
                            @foreach ($patologias as $key => $label)
                                <label class="patologia-item">
                                    <input
                                        type="checkbox"
                                        name="patologias[{{ $key }}]"
                                        value="1"
                                        @checked(old("patologias.$key", $dados['patologias'][$key] ?? false))
                                    >
                                    <span class="check-box" aria-hidden="true"></span>
                                    {{ $label }}
                                </label>
                            @endforeach
                        </div>
                    </section>

                    {{-- ════════════════════════════════════════
                         SEÇÃO 3 · Histórico clínico
                    ════════════════════════════════════════ --}}
                    <section class="cadastro-card secao-card">
                        <div class="secao-header">
                            <span class="secao-numero" aria-hidden="true">3</span>
                            <div>
                                <h2 class="secao-titulo">Histórico clínico</h2>
                                <p class="secao-subtitulo">Responda Sim ou Não para cada item.</p>
                            </div>
                        </div>

                        <div class="historico-lista">
                            @foreach ($historico as [$pergunta, $campoSim, $campoDesc])
                                <div class="historico-item">
                                    <span class="historico-pergunta">{{ $pergunta }}</span>

                                    <div class="historico-toggle" role="group" aria-label="{{ $pergunta }}">
                                        {{-- Não --}}
                                        <button
                                            type="button"
                                            class="btn-toggle"
                                            :class="{ 'ativo': !hist.{{ $campoSim }} }"
                                            @click="hist.{{ $campoSim }} = false"
                                            :aria-pressed="!hist.{{ $campoSim }}"
                                        >Não</button>

                                        {{-- Sim --}}
                                        <button
                                            type="button"
                                            class="btn-toggle"
                                            :class="{ 'ativo': hist.{{ $campoSim }} }"
                                            @click="hist.{{ $campoSim }} = true"
                                            :aria-pressed="hist.{{ $campoSim }}"
                                        >Sim</button>

                                        {{-- hidden input que envia o valor real --}}
                                        <input type="hidden" name="{{ $campoSim }}" :value="hist.{{ $campoSim }} ? 1 : 0">
                                    </div>

                                    @if ($campoDesc)
                                        <div class="historico-descreva" x-show="hist.{{ $campoSim }}" x-cloak>
                                            <div class="field field-full" style="margin:0">
                                                <textarea
                                                    name="{{ $campoDesc }}"
                                                    class="saude-textarea"
                                                    placeholder="Descreva"
                                                    maxlength="1000"
                                                    :required="hist.{{ $campoSim }}"
                                                >{{ $campo($campoDesc) }}</textarea>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                @if (!$loop->last)
                                    <hr class="divisor">
                                @endif
                            @endforeach
                        </div>
                    </section>

                    {{-- ════════════════════════════════════════
                         SEÇÃO 4 · Declaração e assinatura
                    ════════════════════════════════════════ --}}
                    <section class="cadastro-card secao-card">
                        <div class="secao-header">
                            <span class="secao-numero" aria-hidden="true">4</span>
                            <div>
                                <h2 class="secao-titulo">Declaração e assinatura</h2>
                                <p class="secao-subtitulo">Confirmação de veracidade das informações de saúde.</p>
                            </div>
                        </div>

                        <label class="termo-caixa" style="margin-bottom: 14px">
                            <input type="checkbox" name="declaracao_aceita" value="1" required>
                            <span class="check-visual" aria-hidden="true"></span>
                            <span>Declaro que as informações acima prestadas são totalmente verdadeiras.</span>
                        </label>

                        <div class="assinatura-bloco">
                            <span class="rotulo-grupo">Assinatura</span>
                            <label
                                class="assinatura-area"
                                for="assinatura_saude"
                                @dragover.prevent
                                @drop.prevent="soltarArquivo($event)"
                            >
                                <span class="assinatura-icone" aria-hidden="true">
                                    <svg viewBox="0 0 24 24" fill="none">
                                        <path d="M12 16V5m0 0-4 4m4-4 4 4M5 19h14" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </span>
                                <span class="assinatura-texto" x-show="!arquivoNome && !assinaturaSalva">
                                    <strong>Clique para carregar a assinatura</strong> ou arraste a imagem aqui
                                </span>
                                <span class="assinatura-texto" x-show="arquivoNome" x-cloak>
                                    <strong x-text="arquivoNome"></strong>
                                </span>
                                <span class="assinatura-texto" x-show="!arquivoNome && assinaturaSalva" x-cloak>
                                    <strong>Assinatura já enviada.</strong> Clique para substituir
                                </span>
                                <small>PNG ou JPG, até 7 MB</small>
                                <input
                                    id="assinatura_saude"
                                    name="assinatura"
                                    type="file"
                                    accept="image/png,image/jpeg"
                                    x-ref="arquivo"
                                    @change="arquivoNome = $event.target.files[0]?.name ?? ''"
                                >
                            </label>
                        </div>
                    </section>

                    {{-- Navegação --}}
                    <div class="cadastro-actions cadastro-actions-split">
                        <a class="button button-secondary" href="{{ route('matricula.endereco') }}">Voltar</a>
                        <button class="button button-primary" type="submit">Próxima</button>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('fichaSaudeApp', (historicoInicial) => ({
                hist: { ...historicoInicial },
                assinaturaSalva: @js($assinaturaSalva),
                arquivoNome: '',

                soltarArquivo(evento) {
                    const arquivo = evento.dataTransfer.files[0];
                    if (!arquivo) return;
                    this.$refs.arquivo.files = evento.dataTransfer.files;
                    this.arquivoNome = arquivo.name;
                },
            }));
        });
    </script>
</body>
</html>
