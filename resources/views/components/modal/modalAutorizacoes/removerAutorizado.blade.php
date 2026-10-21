<div x-show="removendo" x-cloak style="display:none;" class="modalOverlay"
    @keydown.escape.window="removendo = null">
    <div @click.away="removendo = null" class="modalBox">
        <button type="button" @click="removendo = null" class="modalFechar" aria-label="Fechar">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
        <h3 class="modalTitulo">Remover autorizado</h3>
        <p class="modalTexto">
            Essa pessoa deixa de poder buscar a criança (não pode ser desfeita).
        </p>
        <div class="modalAfetado">
            <p class="modalAfetadoNome" x-text="removendo ? removendo.nome : ''"></p>
            <p class="modalAfetadoSub" x-text="removendo ? removendo.parentesco : ''"></p>
        </div>
        <div class="modalAcoes">
            <button type="button" @click="removendo = null" class="modalBtnCancelar">Cancelar</button>
            <button type="button" @click="excluir(removendo)" class="modalBtnRemover">Remover</button>
        </div>
    </div>
</div>
