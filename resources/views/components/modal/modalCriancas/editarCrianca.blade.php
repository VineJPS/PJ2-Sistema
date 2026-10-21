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
        <p class="text-slate-500 text-sm leading-relaxed mb-6">Atualize o nome desta criança.</p>

        <form @submit.prevent="salvarNome()" class="space-y-4">
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
