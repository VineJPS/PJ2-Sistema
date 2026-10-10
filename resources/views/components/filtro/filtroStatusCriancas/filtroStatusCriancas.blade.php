@push('styles')
    <link rel="stylesheet" href="{{ asset('css/filtroStatusCriancas.css') }}">
@endpush

<div class="secaoFiltro">
    <!-- Botão de Filtro -->
    <button
        type="button"
        class="btnFiltro"
        @click="showFiltro = !showFiltro"
        title="Abrir filtros"
    >
        <img src="{{ asset('assets/filtro.svg') }}" alt="Filtro" class="iconeFiltro" />
        <span style = "font-weight: 500;">Filtro</span>
        <img src="{{ asset('assets/arrow-down.svg') }}" alt="Seta para baixo" class="iconeSeta" />
    </button>

    <!-- Modal Popup do Filtro -->
    <div x-show="showFiltro" 
         @click.away="showFiltro = false"
         x-transition
         class="modalFiltro"
         style="display: none;">
        <div class="filtroContent">
            <span style="font-size: 1.15rem; font-weight: 400; margin-bottom: 0.5rem; display: block;">Filtrar por status</span>
            <div class="filtroOpcoes">
                <label class="filtroLabel">
                    <input
                        type="checkbox"
                        :checked="filtrosSelecionados.length === 0"
                        @change="handleLimparFiltros()"
                    />
                    Todas
                </label>
                <label class="filtroLabel">
                    <input
                        type="checkbox"
                        :checked="filtrosSelecionados.includes('Inativo')"
                        @change="handleFiltroChange('Inativo')"
                    />
                    Inativas
                </label>
                <label class="filtroLabel">
                    <input
                        type="checkbox"
                        :checked="filtrosSelecionados.includes('Ativo')"
                        @change="handleFiltroChange('Ativo')"
                    />
                    Ativas
                </label>
                <label class="filtroLabel">
                    <input
                        type="checkbox"
                        :checked="filtrosSelecionados.includes('Pendente')"
                        @change="handleFiltroChange('Pendente')"
                    />
                    Pendentes
                </label>
            </div>
            <div class="botoesFiltro">
                <button type="button" class="btnAplicar" @click="handleAplicarFiltros()">
                    Aplicar filtros
                </button>
                <button type="button" class="btnLimpar" @click="handleLimparFiltros()">
                    Limpar filtros
                </button>
            </div>
        </div>
    </div>
</div>