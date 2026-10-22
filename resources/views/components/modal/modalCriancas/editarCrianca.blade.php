<div x-show="editando" x-cloak style="display: none;"
    class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm p-4"
    @keydown.escape.window="editando = null">
    <div @click.away="editando = null"
        class="bg-white w-full max-w-[520px] rounded-[24px] shadow-2xl border border-slate-100 p-8 relative">
        <button type="button" @click="editando = null"
            class="absolute top-6 right-6 text-slate-400 hover:text-slate-600 transition-colors cursor-pointer">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>

        <h3 class="text-xl font-bold text-slate-900 mb-1">Editar criança</h3>
        <p class="text-slate-500 text-sm leading-relaxed mb-6">Atualize o nome e a foto desta criança.</p>

        <form @submit.prevent="salvarNome()" class="space-y-4">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-full bg-slate-100 border border-slate-200 overflow-hidden flex items-center justify-center shrink-0">
                    <img x-show="editando && editando.foto" :src="editando ? editando.foto : ''" alt="Foto da criança" class="w-full h-full object-cover" />
                    <svg x-show="!(editando && editando.foto)" class="w-7 h-7 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
                <input type="file" x-ref="inputFotoModal" accept="image/png,image/jpeg" class="hidden" @change="enviarFoto($event)" />
                <button type="button" @click="$refs.inputFotoModal.click()" :disabled="enviandoFoto"
                    class="py-2 px-4 rounded-xl border border-slate-200 font-semibold text-slate-700 hover:bg-slate-50 transition-colors text-sm cursor-pointer disabled:opacity-50"
                    x-text="enviandoFoto ? 'Enviando...' : (editando && editando.foto ? 'Alterar foto' : 'Adicionar foto')"></button>
                <button type="button" @click="removerFoto()" :disabled="!(editando && editando.foto)" title="Remover foto"
                    class="p-2 rounded-xl border border-slate-200 text-red-500 hover:bg-red-50 transition-colors cursor-pointer disabled:opacity-40 disabled:cursor-not-allowed disabled:hover:bg-transparent">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
            </div>

            <div>
                <label for="novo-nome" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Nome completo</label>
                <input id="novo-nome" type="text" x-model="novoNome" maxlength="150" required
                    placeholder="Nome da criança"
                    class="w-full bg-[#fafbfd] border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:border-blue-600 transition-colors" />
                <p class="text-xs text-red-600 mt-2" x-text="erroNome" x-show="erroNome"></p>
            </div>

            <div class="grid grid-cols-2 gap-4 pt-4">
                <button type="button" @click="editando = null"
                    class="w-full py-3 px-4 rounded-xl border border-slate-200 font-semibold text-slate-700 hover:bg-slate-50 transition-colors text-sm cursor-pointer">Cancelar</button>
                <button type="submit"
                    class="w-full py-3 px-4 rounded-xl bg-[#2563eb] hover:bg-blue-700 text-white font-semibold transition-colors text-sm shadow-sm cursor-pointer">Salvar alterações</button>
            </div>
        </form>
    </div>
</div>
