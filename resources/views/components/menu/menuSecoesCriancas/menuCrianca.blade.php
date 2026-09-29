<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $crianca['nome'] ?? 'Criança' }}</title>

    <link rel="stylesheet" href="{{ asset('css/menuCrianca.css') }}">

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body x-cloak>
    <div id="id-menu-crianca" class="page-container" x-data="menuCrianca()" @alpine:init="init()">

        <!-- Header com as informações da criança -->
        <div class="header-perfil">
            <div class="info-principal">
                <div class="avatar-circle" :class="getAvatarCircleClass()">
                    <div class="avatar-icon" :class="getStatusIconClass()"></div>
                </div>
                <div class="texto-perfil">
                    <h1 class="nome-crianca">{{ $crianca['nome'] ?? 'Criança não selecionada' }}</h1>
                    <p class="detalhes-entrada">
                        {{ $crianca['idade'] ?? '--' }} anos · Entrou em {{ $crianca['dataEntrada'] ?? '12/02/2020' }}
                    </p>
                </div>
            </div>

            <div class="status-rematricula">
                <div class = "status">
                    <span class="badge-status" :class="getBadgeClass()">
                        <span class="ponto-status">●</span> 
                        <span x-text="getStatusLabel()"></span>
                    </span>
                </div>
    
            
                <div class="rematricula">
                    <a href="#" class="btnRematricula">
                        <img src="{{ asset('assets/refresh.svg') }}" alt="Rematrícula">
                        Rematricular    
                    </a>
                </div>
            </div>
        </div>

        <!-- Abas do menu: serve para navegar entre as diferentes seções da criança -->
        <div class="tabs-wrapper">
            <div class="tabs-container" x-ref="tabsContainer">
                <template x-for="(tab, idx) in tabs" :key="tab">
                    <button
                        class="tab-item"
                        :class="{ 'tab-ativa': activeTab === tab }"
                        @click="setActiveTab(tab)"
                        x-init="tabsRef[idx] = $el"
                    >
                        <span x-text="tab"></span>
                    </button>
                </template>
                <span 
                    class="active-line"
                    :style="{ left: indicatorStyle.left + 'px', width: indicatorStyle.width + 'px' }"
                ></span>
            </div>

            <button class="btn-icone-aba" @click="handleScrollRight()" aria-label="Avançar abas">
                <svg class="icone-seta-menu" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
        </div>

        <!-- Conteúdo dinâmico das abas -->
        <div class="tab-content">

            <!-- Aba Matrícula -->
            <div x-show="activeTab === 'Matrícula'" style="display: none;">
                @if(file_exists(resource_path('views/components/secoes/criancas/matricula/matricula.blade.php')))
                    @include('components.secoes.criancas.matricula.matricula', ['criancaId' => $crianca['id']])
                @else
                    <div class="placeholder-aba">
                        <p>Conteúdo de Matrícula não preenchido.</p>
                    </div>
                @endif
            </div>
    
            <!-- Aba Responsáveis -->
            <div x-show="activeTab === 'Responsáveis'" style="display: none;">
                @if(file_exists(resource_path('views/components/secoes/criancas/responsaveis/responsaveis.blade.php')))
                    @include('components.secoes.criancas.responsaveis.responsaveis', ['criancaId' => $crianca['id']])
                @else
                    <div class="placeholder-aba">
                        <p>Conteúdo de Responsáveis não preenchido.</p>
                    </div>
                @endif
            </div>

            <!-- Aba Saúde -->
            <div x-show="activeTab === 'Saúde'" style="display: none;">
                @if(file_exists(resource_path('views/components/secoes/criancas/saude/saude.blade.php')))
                    @include('components.secoes.criancas.saude.saude', ['criancaId' => $crianca['id']])
                @else
                    <div class="placeholder-aba">
                        <p>Conteúdo de Saúde não preenchido.</p>
                    </div>
                @endif
            </div>

            <!-- Aba Autorização -->
            <div x-show="activeTab === 'Autorização'" style="display: none;">
                @if(file_exists(resource_path('views/components/secoes/criancas/autorizacoes/autorizacoes.blade.php')))
                    @include('components.secoes.criancas.autorizacoes.autorizacoes', ['criancaId' => $crianca['id']])
                @else
                    <div class="placeholder-aba">
                        <p>Conteúdo de Autorização não preenchido.</p>
                    </div>
                @endif
            </div>

            <!-- Aba Relatos -->
            <div x-show="activeTab === 'Relatos'" style="display: none;">
                @if(file_exists(resource_path('views/components/secoes/criancas/relatos/relatos.blade.php')))
                    @include('components.secoes.criancas.relatos.relatos', ['criancaId' => $crianca['id']])
                @else
                    <div class="placeholder-aba">
                        <p>Conteúdo de Relatos não preenchido.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        function menuCrianca() {
            return {
                activeTab: 'Matrícula',
                tabs: ['Matrícula', 'Responsáveis', 'Saúde', 'Autorização', 'Relatos'],
                crianca: @json($crianca),
                indicatorStyle: { left: 0, width: 0 },
                tabsRef: [],
                tabsContainer: null,

                init() {
                    this.$watch('activeTab', () => this.updateIndicator());
                    this.$nextTick(() => this.updateIndicator());
                },

                setActiveTab(tab) {
                    this.activeTab = tab;
                },

                updateIndicator() {
                    this.$nextTick(() => {
                        const currentIndex = this.tabs.indexOf(this.activeTab);
                        const currentTab = this.tabsRef[currentIndex];
                        if (currentTab) {
                            this.indicatorStyle = {
                                left: currentTab.offsetLeft,
                                width: currentTab.offsetWidth,
                            };
                        }
                    });
                },

                handleScrollRight() {
                    if (this.$refs.tabsContainer) {
                        this.$refs.tabsContainer.scrollBy({ left: 200, behavior: 'smooth' });
                    }
                },

                getBadgeClass() {
                    const status = this.crianca?.status;
                    switch (status) {
                        case 'Inativo':
                            return 'badge-inativo';
                        case 'Pendente':
                            return 'badge-alergico';
                        default:
                            return 'badge-ativo';
                    }
                },

                getAvatarCircleClass() {
                    const status = this.crianca?.status;
                    switch (status) {
                        case 'Inativo':
                            return 'avatar-circle-inativo';
                        case 'Pendente':
                            return 'avatar-circle-alergico';
                        default:
                            return 'avatar-circle-ativo';
                    }
                },

                getStatusIconClass() {
                    const status = this.crianca?.status;
                    switch (status) {
                        case 'Inativo':
                            return 'icon-inativo';
                        case 'Pendente':
                            return 'icon-alergico';
                        default:
                            return 'icon-ativo';
                    }
                },

                getStatusLabel() {
                    const status = this.crianca?.status;
                    switch (status) {
                        case 'Inativo':
                            return 'Inativo';
                        case 'Pendente':
                            return 'Pendente';
                        default:
                            return 'Ativo';
                    }
                }
            };
        }
    </script>
</body>
</html>
