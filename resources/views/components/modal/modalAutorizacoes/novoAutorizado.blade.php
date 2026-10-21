<div x-show="formAberto" x-cloak style="display:none;" class="modalOverlay"
    @keydown.escape.window="formAberto = false">
    <form @click.away="formAberto = false" @submit.prevent="adicionar()" class="modalBox" novalidate>
        <button type="button" @click="formAberto = false" class="modalFechar" aria-label="Fechar">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
        <h3 class="modalTitulo">Novo autorizado</h3>
        <p class="modalTexto">Informe quem pode buscar a criança.</p>

        <label class="modalCampo">
            <span>Nome</span>
            <input type="text" x-model="novo.nome" placeholder="Nome completo" maxlength="255" class="modalInput" />
        </label>
        <label class="modalCampo">
            <span>Parentesco</span>
            <input type="text" x-model="novo.parentesco" placeholder="Ex: Avó" maxlength="100" class="modalInput" />
        </label>
        <label class="modalCampo">
            <span>Telefone</span>
            <input type="tel" inputmode="tel" x-model="novo.telefone"
                @input="$event.target.value = novo.telefone = mascararTelefone($event.target.value)"
                placeholder="(15) 91234-5678" class="modalInput" />
        </label>

        <p class="modalErro" x-show="erroForm" x-text="erroForm"></p>

        <div class="modalAcoes">
            <button type="button" @click="formAberto = false" class="modalBtnCancelar">Cancelar</button>
            <button type="submit" class="modalBtnSalvar">Adicionar</button>
        </div>
    </form>
</div>
