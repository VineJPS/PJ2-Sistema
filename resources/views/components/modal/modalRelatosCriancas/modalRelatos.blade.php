
@once
    <link rel="stylesheet" href="{{ asset('css/modalCriancaRelatos.css') }}">
@endonce

{{-- Fragmento reutilizável do Modal de Relato. Depende de x-data pai com 'relatoSelecionado' --}}
<div class="overlayModal" x-show="relatoSelecionado !== null" x-cloak @click="relatoSelecionado = null" style="display: none;">
    <template x-if="relatoSelecionado">
        <div class="cardModal" @click.stop>
            <button type="button" class="btnFechar" @click="relatoSelecionado = null" aria-label="Fechar">✕</button>

            <div class="headerInfo">
                <p class="linhaInfo">
                    <span class="labelHeader">FUNCIONÁRIO:</span>
                    <span class="valorFuncionario" x-text="relatoSelecionado.relato_funcionario"></span>
                </p>
                <p class="linhaInfo">
                    <span class="labelHeader">DATA:</span>
                    <span class="valorData" x-text="relatoSelecionado.relato_data"></span>
                </p>
            </div>

            <p class="textoCorpo" x-text="relatoSelecionado.relato_texto_completo"></p>
        </div>
    </template>
</div>
