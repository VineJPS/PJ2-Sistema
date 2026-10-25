<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Crianças</title>

    <!-- Tailwind CSS (necessário para a sidebar e estrutura flex) -->
    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="stylesheet" href="{{ asset('css/homeCrianca.css') }}">

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-[#f8fafc] font-sans antialiased flex h-screen overflow-hidden">

    <!-- Incluindo o Componente Sidebar Fixa -->
    <x-sidebar />

@php
    $criancasData = app(\App\Services\CriancaTelaService::class)->lista();
@endphp

    @if (session('success') || session('sucesso'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100 transform translate-y-0"
            x-transition:leave-end="opacity-0 transform -translate-y-2"
            role="status"
            class="fixed top-6 right-6 left-[19.5rem] z-50 p-4 bg-green-50 border border-green-200 text-green-700 text-sm font-medium rounded-xl flex items-center justify-between gap-3 shadow-lg">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-green-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span>{{ session('success') ?? session('sucesso') }}</span>
            </div>
            <button type="button" @click="show = false" class="text-green-500 hover:text-green-700 cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    @endif

    <!-- Conteúdo Principal Envolvido -->
    <main class="flex-1 overflow-y-auto p-8 md:p-12">
        <div id="id-crianca" 
             class="containerCrianca"
             x-data="{
                searchNome: '',
                showFiltro: false,
                filtrosSelecionados: [],
                criancasTodas: {{ json_encode($criancasData) }},
                criancasFiltradas: {{ json_encode($criancasData) }},
                
                handleFiltroChange(status) {
                    if (this.filtrosSelecionados.includes(status)) {
                        this.filtrosSelecionados = this.filtrosSelecionados.filter(s => s !== status);
                    } else {
                        this.filtrosSelecionados.push(status);
                    }
                },
                
                handleAplicarFiltros() {
                    if (this.filtrosSelecionados.length === 0) {
                        this.criancasFiltradas = this.criancasTodas;
                    } else {
                        this.criancasFiltradas = this.criancasTodas.filter(c => this.filtrosSelecionados.includes(c.status));
                    }
                    this.showFiltro = false;
                },
                
                handleLimparFiltros() {
                    this.filtrosSelecionados = [];
                    this.criancasFiltradas = this.criancasTodas;
                },
                
                get criancasComFiltroNome() {
                    return this.criancasFiltradas.filter(c => 
                        c.nome.toLowerCase().includes(this.searchNome.toLowerCase())
                    );
                },

                abrirDetalhes(id) {
                    window.location.href = `/crianca/menu/${id}`;
                },

                menuAbertoId: null,

                statusNormalizado(crianca) {
                    return crianca.status === 'Ativa' ? 'Ativo' : crianca.status;
                },

                outrosStatus(crianca) {
                    return ['Ativo', 'Inativo', 'Pendente'].filter(s => s !== this.statusNormalizado(crianca));
                },

                alterarStatus(crianca, novoStatus) {
                    this.menuAbertoId = null;
                    this.enviar('PATCH', `/crianca/${crianca.id}/status`, { status: novoStatus })
                        .then(() => this.atualizarLocal(crianca.id, { status: novoStatus }))
                        .catch(() => {});
                },

                removendo: null,
                erroGeral: '',

                removerCrianca(crianca) {
                    this.menuAbertoId = null;
                    this.removendo = crianca;
                },

                confirmarRemocao() {
                    const crianca = this.removendo;
                    this.removendo = null;
                    this.enviar('DELETE', `/crianca/${crianca.id}`).then(() => {
                        this.criancasTodas = this.criancasTodas.filter(c => c.id !== crianca.id);
                        this.criancasFiltradas = this.criancasFiltradas.filter(c => c.id !== crianca.id);
                    }).catch(() => {});
                },

                editando: null,
                novoNome: '',
                erroNome: '',
                enviandoFoto: false,

                async enviarFoto(evento) {
                    const arquivo = evento.target.files[0];
                    evento.target.value = '';
                    if (!arquivo) return;
                    this.erroNome = '';
                    this.enviandoFoto = true;
                    const corpo = new FormData();
                    corpo.append('foto', arquivo);
                    try {
                        const resposta = await fetch(`/crianca/${this.editando.id}/foto`, {
                            method: 'POST',
                            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                            body: corpo,
                        });
                        const dados = await resposta.json().catch(() => ({}));
                        if (!resposta.ok) {
                            this.erroNome = dados.errors ? Object.values(dados.errors)[0][0] : 'Não foi possível enviar a foto.';
                            return;
                        }
                        this.atualizarLocal(this.editando.id, { foto: dados.foto });
                    } finally {
                        this.enviandoFoto = false;
                    }
                },

                removerFoto() {
                    const id = this.editando.id;
                    this.enviar('DELETE', `/crianca/${id}/foto`)
                        .then(() => this.atualizarLocal(id, { foto: null }))
                        .catch((erro) => { this.erroNome = erro.message; });
                },

                abrirEdicao(crianca) {
                    this.menuAbertoId = null;
                    this.editando = crianca;
                    this.novoNome = crianca.nome;
                    this.erroNome = '';
                },

                salvarNome() {
                    const crianca = this.editando;
                    this.enviar('PATCH', `/crianca/${crianca.id}/nome`, { nome: this.novoNome })
                        .then((dados) => {
                            this.atualizarLocal(crianca.id, { nome: dados.nome });
                            this.editando = null;
                        })
                        .catch((erro) => { this.erroNome = erro.message; });
                },

                // As duas listas são objetos distintos, então a alteração vale para ambas
                atualizarLocal(id, campos) {
                    [this.criancasTodas, this.criancasFiltradas].forEach(lista => {
                        const item = lista.find(c => c.id === id);
                        if (item) Object.assign(item, campos);
                    });
                },

                async enviar(metodo, url, corpo = null) {
                    const resposta = await fetch(url, {
                        method: metodo,
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: corpo ? JSON.stringify(corpo) : null,
                    });
                    const dados = await resposta.json().catch(() => ({}));
                    if (!resposta.ok) {
                        const primeiro = dados.errors ? Object.values(dados.errors)[0][0] : null;
                        const erro = new Error(primeiro || dados.message || 'Não foi possível concluir a ação.');
                        if (!this.editando) {
                            this.erroGeral = erro.message;
                            setTimeout(() => this.erroGeral = '', 5000);
                        }
                        throw erro;
                    }
                    return dados;
                }
             }">

            <!-- Modal de edição do nome -->
            @include('components.modal.modalCriancas.editarCrianca')

            <!-- Modal de remoção -->
            @include('components.modal.modalCriancas.removerCrianca')

            <!-- Toast de erro -->
            <div x-show="erroGeral" x-cloak style="display: none;" role="alert"
                x-transition:leave="transition ease-in duration-300"
                class="fixed top-6 right-6 z-50 max-w-sm p-4 bg-red-50 border border-red-200 text-red-700 text-sm font-medium rounded-xl flex items-center justify-between gap-3 shadow-lg">
                <span x-text="erroGeral"></span>
                <button type="button" @click="erroGeral = ''" class="cursor-pointer" aria-label="Fechar">✕</button>
            </div>

            <!-- Topo Ações -->
            <div class="topoAcoes">
                <div class="wrapperInputPesquisa">
                    <img src="{{ asset('assets/search.png') }}" alt="Buscar" class="iconeBusca" />
                    <input
                        type="text"
                        placeholder="Buscar nome..."
                        x-model="searchNome"
                        class="inputPesquisa"
                    />
                </div>

                <!-- Importa a parcial do Filtro que já carrega o filtroStatus.css internamente -->
                @include('components.filtro.filtroStatusCriancas.filtroStatusCriancas')

                <a href="{{ route('matricula.crianca') }}" class="btnNovoCadastro">
                    <img src="{{ asset('assets/plus.svg') }}" alt="Novo cadastro" class="iconeNovoCadastro" />
                    Novo cadastro
                </a>
            </div>

            <!-- Grid de Crianças -->
            <div class="gridCriancas">
                <template x-for="crianca in criancasComFiltroNome" :key="crianca.id">
                    <div
                        class="cardCrianca"
                        :class="{
                            'cardAtivo': crianca.status === 'Ativo',
                            'cardInativo': crianca.status === 'Inativo',
                            'cardAlergico': crianca.status === 'Ativa' || crianca.status === 'Pendente'
                        }"
                        @click="abrirDetalhes(crianca.id)"
                        :style="menuAbertoId === crianca.id ? 'z-index: 30' : ''"
                        role="button"
                        tabindex="0"
                        @keydown.enter="abrirDetalhes(crianca.id)"
                        @keydown.space.prevent="abrirDetalhes(crianca.id)"
                    >
                        <div class="avatarCircle" :class="{ 'comFoto': crianca.foto }">
                            <template x-if="crianca.foto">
                                <img :src="crianca.foto" :alt="`Foto de ${crianca.nome}`" class="avatarFoto" loading="lazy" />
                            </template>
                            <div class="avatarIcon" x-show="!crianca.foto"
                                 :class="{
                                    'iconAtivo': crianca.status === 'Ativo',
                                    'iconInativo': crianca.status === 'Inativo',
                                    'iconAlergico': crianca.status === 'Ativa' || crianca.status === 'Pendente'
                                 }"
                                 :style="`mask-image: url('{{ asset('assets/person 2.png') }}'); -webkit-mask-image: url('{{ asset('assets/person 2.png') }}');`"
                            ></div>
                        </div>
                        <div class="infoCrianca">
                            <h3 class="nomeCrianca" x-text="crianca.nome"></h3>
                            <p class="detalhesCrianca">
                                <span x-text="crianca.idade + ' anos'"></span>
                                <span class="pontoSeparador">•</span>
                                <span :class="{
                                    'statusAtivo': crianca.status === 'Ativo',
                                    'statusInativo': crianca.status === 'Inativo',
                                    'statusAlergico': crianca.status === 'Ativa' || crianca.status === 'Pendente'
                                }" x-text="crianca.status"></span>
                            </p>
                        </div>

                        <!-- Menu de ações (três pontos) -->
                        <div class="menuCrianca" @click.stop @keydown.enter.stop @keydown.space.stop>
                            <button type="button" title="Mais opções"
                                @click="menuAbertoId = menuAbertoId === crianca.id ? null : crianca.id"
                                class="text-slate-400 hover:text-slate-600 p-1.5 rounded-lg hover:bg-slate-100 transition-colors cursor-pointer">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                </svg>
                            </button>

                            <div x-show="menuAbertoId === crianca.id" @click.away="menuAbertoId = null" style="display: none;"
                                class="absolute right-0 top-9 z-20 w-56 bg-white rounded-xl shadow-xl border border-slate-100 py-1.5 text-left text-xs font-medium text-slate-700 divide-y divide-slate-100">
                                <div class="py-1">
                                    <button type="button" @click="abrirEdicao(crianca)"
                                        class="w-full text-left px-4 py-2 hover:bg-slate-50 flex items-center gap-2.5 text-slate-700 cursor-pointer">
                                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        <span x-text="`Editar ${crianca.nome}`" class="truncate"></span>
                                    </button>
                                </div>

                                <div class="py-1">
                                    <template x-for="s in outrosStatus(crianca)" :key="s">
                                        <button type="button" @click="alterarStatus(crianca, s)"
                                            :class="{
                                                'statusAtivo': s === 'Ativo',
                                                'statusInativo': s === 'Inativo',
                                                'statusAlergico': s === 'Pendente'
                                            }"
                                            class="w-full text-left px-4 py-2 hover:bg-slate-50 flex items-center gap-2.5 cursor-pointer">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                            </svg>
                                            <span x-text="`Tornar ${s === 'Ativo' ? 'ativo' : s === 'Inativo' ? 'inativo' : 'pendente'}`"></span>
                                        </button>
                                    </template>
                                </div>

                                <div class="py-1">
                                    <button type="button" @click="removerCrianca(crianca)"
                                        class="w-full text-left px-4 py-2 hover:bg-red-50 flex items-center gap-2.5 text-red-600 cursor-pointer">
                                        <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        Remover criança
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </main>

</body>
</html>