<div 
    x-show="modalAberta === 'desativar'" 
    x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm p-4"
    @keydown.escape.window="modalAberta = null"
>
    <div 
        @click.away="modalAberta = null"
        class="bg-white w-full max-w-[500px] rounded-[24px] shadow-2xl border border-slate-100 p-8 relative"
    >
        
        <!-- Botão Fechar (X) -->
        <button 
            type="button" 
            @click="modalAberta = null" 
            class="absolute top-6 right-6 text-slate-400 hover:text-slate-600 transition-colors cursor-pointer"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>

        <h3 class="text-xl font-bold text-slate-900 mb-2">Desativar usuário</h3>
        <p class="text-slate-500 text-sm leading-relaxed mb-6">
            A pessoa perde o acesso ao painel imediatamente. Você pode reativar quando quiser.
        </p>

        <!-- Card do Usuário Afetado -->
        <div class="flex items-center gap-3 p-4 rounded-2xl border border-slate-200/80 mb-8 bg-slate-50/50">
            <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold text-xs shrink-0">
                <span x-text="usuarioSelecionado ? ((usuarioSelecionado.name || usuarioSelecionado.nome || 'RS').substring(0, 2).toUpperCase()) : 'RS'"></span>
            </div>
            <div>
                <p class="font-semibold text-slate-900 text-sm" x-text="usuarioSelecionado ? (usuarioSelecionado.name || usuarioSelecionado.nome) : 'Usuário'"></p>
                <p class="text-xs text-slate-400" x-text="usuarioSelecionado ? usuarioSelecionado.email : ''"></p>
            </div>
        </div>

        <!-- Formulário e Botões de Ação -->
        <form method="POST" :action="'{{ url('admin/usuarios') }}/' + (usuarioSelecionado ? usuarioSelecionado.id : '') + '/desativar'">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-2 gap-4">
                <button 
                    type="button" 
                    @click="modalAberta = null" 
                    class="w-full py-3.5 px-4 rounded-xl border border-slate-200 font-semibold text-slate-700 hover:bg-slate-50 transition-colors text-sm cursor-pointer"
                >
                    Cancelar
                </button>
                <button 
                    type="submit" 
                    class="w-full py-3.5 px-4 rounded-xl bg-[#b91c1c] hover:bg-red-800 text-white font-semibold transition-colors text-sm shadow-sm cursor-pointer"
                >
                    Desativar
                </button>
            </div>
        </form>

    </div>
</div>