<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Autorizações e assinatura - Recanto Betel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/cadastroCrianca.css') }}">
    <link rel="stylesheet" href="{{ asset('css/cadastroResponsaveis.css') }}">
    <link rel="stylesheet" href="{{ asset('css/cadastroAutorizacoesAssinaturas.css') }}">
</head>
@php
    $origem = old() ? old() : ($dados ?? []);
    $inicial = [
        'autorizaFrequencia' => (bool) ($origem['autoriza_frequencia'] ?? false),
        'saiSozinho' => (bool) ($origem['sai_sozinho'] ?? true),
        'autorizados' => collect($origem['autorizados'] ?? [])->map(fn ($a) => [
            'nome' => $a['nome'] ?? '', 'parentesco' => $a['parentesco'] ?? '', 'telefone' => $a['telefone'] ?? '',
        ])->values()->all(),
        'termoAceito' => (bool) ($origem['termo_aceito'] ?? false),
        'assinaturaSalva' => !empty($dados['assinatura_path'] ?? null),
    ];
    $dataAssinatura = old('data_assinatura', $dados['data_assinatura'] ?? '');
@endphp
<body class="cadastro-body">
    <div class="cadastro-layout">
        <x-sidebar />

        <main class="cadastro-main">
            <div class="cadastro-wrap cadastro-wrap-largo" x-data="autorizacoesComponent(@js($inicial))">
                <header class="cadastro-heading">
                    <h1>Autorizações e assinatura</h1>
                    <p>Permissões, termo de compromisso e assinatura do responsável.</p>
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

                <form class="cadastro-form" method="POST" action="{{ route('matricula.autorizacoes.salvar') }}" enctype="multipart/form-data" aria-label="Autorizações e assinatura">
                    @csrf

                    <!-- Autorizações -->
                    <section class="cadastro-card secao-card autorizacao-card">
                        <div class="autorizacao-linha">
                            <div>
                                <h2 id="titulo-frequencia">Autorização de frequência</h2>
                                <p>Autorizo meu filho(a) a frequentar de segunda a sexta-feira, das 13h às 17h, o Projeto Samaritano, pelo prazo de 1 mês de adaptação, e a participar de todas as atividades do plano anual, passeios e atividades fora da instituição, acompanhado(a) pela educadora, funcionários e voluntários.</p>
                            </div>
                            <button type="button" class="switch" role="switch" aria-labelledby="titulo-frequencia" :aria-checked="autorizaFrequencia" :class="{ 'ligado': autorizaFrequencia }" @click="autorizaFrequencia = !autorizaFrequencia"><span></span></button>
                            <input type="hidden" name="autoriza_frequencia" :value="autorizaFrequencia ? 1 : 0">
                        </div>

                        <hr class="divisor">

                        <div class="autorizacao-linha">
                            <div>
                                <h2 id="titulo-sozinho">Autorizo meu filho(a) a ir embora sozinho(a)</h2>
                                <p>Se desmarcado, informe abaixo quem está autorizado a buscar a criança.</p>
                            </div>
                            <button type="button" class="switch" role="switch" aria-labelledby="titulo-sozinho" :aria-checked="saiSozinho" :class="{ 'ligado': saiSozinho }" @click="saiSozinho = !saiSozinho"><span></span></button>
                            <input type="hidden" name="sai_sozinho" :value="saiSozinho ? 1 : 0">
                        </div>

                        <template x-for="(item, i) in autorizados" :key="item.id">
                            <div class="bloco-item bloco-destaque">
                                <button type="button" class="btn-remover" aria-label="Remover pessoa autorizada" @click="autorizados.splice(i, 1)">
                                    <svg viewBox="0 0 24 24" fill="none"><path d="M4 7h16M10 11v6M14 11v6M6 7l1 12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1l1-12M9 7V4h6v3" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </button>

                                <div class="cadastro-fields">
                                    <div class="field field-full">
                                        <label :for="'aut-nome-' + item.id">Nome completo</label>
                                        <input :id="'aut-nome-' + item.id" :name="`autorizados[${i}][nome]`" type="text" placeholder="Nome completo" required x-model="item.nome">
                                    </div>
                                    <div class="field field-half">
                                        <label :for="'aut-parentesco-' + item.id">Grau de parentesco</label>
                                        <input :id="'aut-parentesco-' + item.id" :name="`autorizados[${i}][parentesco]`" type="text" placeholder="Ex: Mãe, Pai, Tia" required x-model="item.parentesco">
                                    </div>
                                    <div class="field field-half">
                                        <label :for="'aut-telefone-' + item.id">Telefone</label>
                                        <input :id="'aut-telefone-' + item.id" :name="`autorizados[${i}][telefone]`" type="tel" placeholder="(00) 00000-0000" required x-model="item.telefone" @input="item.telefone = mascararTelefone($event.target.value)">
                                    </div>
                                </div>
                            </div>
                        </template>

                        <button type="button" class="btn-adicionar" @click="adicionarAutorizado()">
                            <span aria-hidden="true">+</span> Adicionar pessoa autorizada
                        </button>
                    </section>

                    <!-- Termo de compromisso -->
                    <section class="termo-secao">
                        <h2>Termo de compromisso</h2>
                        <p class="termo-sub">Leia e concorde para prosseguir</p>

                        <label class="termo-caixa">
                            <input type="checkbox" name="termo_aceito" value="1" required x-model="termoAceito">
                            <span class="check-visual" aria-hidden="true"></span>
                            <span>Declaro estar ciente e de acordo com o Regulamento do Projeto Samaritano, comprometo-me a incentivar meu(minha) filho(a) a cumpri-lo, e estou ciente da importância de participar de todas as convocações da instituição.</span>
                        </label>
                    </section>

                    <!-- Assinatura -->
                    <section class="cadastro-card secao-card">
                        <div class="cadastro-fields">
                            <div class="field field-half">
                                <label for="data-assinatura">Data da assinatura</label>
                                <input id="data-assinatura" name="data_assinatura" type="date" max="{{ now()->toDateString() }}" value="{{ $dataAssinatura }}" @error('data_assinatura') aria-invalid="true" @enderror>
                            </div>
                        </div>

                        <div class="assinatura-bloco">
                            <span class="rotulo-grupo">Assinatura do responsável</span>
                            <label class="assinatura-area" for="assinatura" @dragover.prevent @drop.prevent="soltarArquivo($event)">
                                <span class="assinatura-icone" aria-hidden="true">
                                    <svg viewBox="0 0 24 24" fill="none"><path d="M12 16V5m0 0-4 4m4-4 4 4M5 19h14" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </span>
                                <span class="assinatura-texto" x-show="!arquivoNome && !assinaturaSalva"><strong>Clique para carregar a assinatura</strong> ou arraste a imagem aqui</span>
                                <span class="assinatura-texto" x-show="arquivoNome" x-cloak><strong x-text="arquivoNome"></strong></span>
                                <span class="assinatura-texto" x-show="!arquivoNome && assinaturaSalva" x-cloak><strong>Assinatura já enviada.</strong> Clique para substituir</span>
                                <small>PNG ou JPG, até 5 MB</small>
                                <input id="assinatura" name="assinatura" type="file" accept="image/png,image/jpeg" x-ref="arquivo" @change="arquivoNome = $event.target.files[0]?.name ?? ''">
                            </label>
                        </div>
                    </section>

                    <div class="cadastro-actions cadastro-actions-split">
                        <a class="button button-secondary" href="{{ route('matricula.saude') }}">Voltar</a>
                        <button class="button button-primary" type="submit">Finalizar</button>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('autorizacoesComponent', (inicial) => {
                let proximoId = 1;
                const novoAutorizado = (dados = {}) => ({ id: proximoId++, nome: '', parentesco: '', telefone: '', ...dados });

                return {
                    autorizaFrequencia: inicial.autorizaFrequencia,
                    saiSozinho: inicial.saiSozinho,
                    termoAceito: inicial.termoAceito,
                    assinaturaSalva: inicial.assinaturaSalva,
                    arquivoNome: '',
                    autorizados: inicial.autorizados.map((a) => novoAutorizado(a)),

                    adicionarAutorizado() { this.autorizados.push(novoAutorizado()); },

                    soltarArquivo(evento) {
                        const arquivo = evento.dataTransfer.files[0];
                        if (!arquivo) return;
                        this.$refs.arquivo.files = evento.dataTransfer.files;
                        this.arquivoNome = arquivo.name;
                    },

                    mascararTelefone(valor) {
                        const d = valor.replace(/\D/g, '').slice(0, 11);
                        if (d.length <= 2) return d ? '(' + d : '';
                        if (d.length <= 6) return `(${d.slice(0, 2)}) ${d.slice(2)}`;
                        if (d.length <= 10) return `(${d.slice(0, 2)}) ${d.slice(2, 6)}-${d.slice(6)}`;
                        return `(${d.slice(0, 2)}) ${d.slice(2, 7)}-${d.slice(7)}`;
                    },
                };
            });
        });
    </script>
</body>
</html>
