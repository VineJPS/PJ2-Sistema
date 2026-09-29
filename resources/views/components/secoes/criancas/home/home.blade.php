<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Crianças</title>

    <link rel="stylesheet" href="{{ asset('css/homeCrianca.css') }}">

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body>

@php
    use Database\Seeders\CriancaDataSeeder;
    
    // Puxar dados de todas as crianças do seeder
    $criancasData = CriancaDataSeeder::getTodosCriancas();
@endphp

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
            <img src="{{ asset('assets/image 14.png') }}" alt="Buscar" class="iconeBusca" />
            <input
                type="text"
                placeholder="Buscar nome..."
                x-model="searchNome"
                class="inputPesquisa"
            />
        </div>

        <!-- Importa a parcial do Filtro que já carrega o filtroStatus.css internamente -->
        @include('components.filtro.filtroStatusCriancas.filtroStatusCriancas')

        <a href="/crianca/cadastro" class="btnNovoCadastro">
            + Novo cadastro
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

</body>
</html>