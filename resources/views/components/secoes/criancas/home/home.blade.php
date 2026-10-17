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
    use Database\Seeders\CriancaDataSeeder;
    
    // Puxar dados de todas as crianças do seeder
    $criancasData = CriancaDataSeeder::getTodosCriancas();
@endphp

    @if (session('success') || session('sucesso'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100 transform translate-y-0"
            x-transition:leave-end="opacity-0 transform -translate-y-2"
            role="status"
            class="fixed top-6 right-6 z-50 max-w-sm p-4 bg-green-50 border border-green-200 text-green-700 text-sm font-medium rounded-xl flex items-center justify-between gap-3 shadow-lg">
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
                }
             }">

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
                        role="button"
                        tabindex="0"
                        @keydown.enter="abrirDetalhes(crianca.id)"
                        @keydown.space.prevent="abrirDetalhes(crianca.id)"
                    >
                        <div class="avatarCircle">
                            <div class="avatarIcon"
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
                    </div>
                </template>
            </div>
        </div>
    </main>

</body>
</html>