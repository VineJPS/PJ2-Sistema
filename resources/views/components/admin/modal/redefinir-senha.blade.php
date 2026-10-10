<div 
    x-show="modalAberta === 'redefinirSenha'" 
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

        <h3 class="text-xl font-bold text-slate-900 mb-2">Redefinir senha</h3>
        <p class="text-slate-500 text-sm leading-relaxed mb-6">
            Enviaremos uma nova senha temporária por e-mail para esta pessoa através do Mailtrap.
        </p>

        <!-- Card do Usuário Afetado -->
        <div class="flex items-center gap-3 p-4 rounded-2xl border border-slate-200/80 mb-8 bg-slate-50/50">
            <div class="w-10 h-10 rounded-full bg-emerald-600 flex items-center justify-center text-white font-bold text-xs shrink-0">
                <span x-text="usuarioSelecionado ? ((usuarioSelecionado.name || usuarioSelecionado.nome || 'CM').substring(0, 2).toUpperCase()) : 'CM'"></span>
            </div>
            <div>
                <p class="font-semibold text-slate-900 text-sm" x-text="usuarioSelecionado ? (usuarioSelecionado.name || usuarioSelecionado.nome) : 'Usuário'"></p>
                <p class="text-xs text-slate-400" x-text="usuarioSelecionado ? usuarioSelecionado.email : ''"></p>
            </div>
        </div>

        <!-- Formulário e Botões de Ação -->
        <form method="POST" :action="'{{ url('admin/usuarios') }}/' + (usuarioSelecionado ? usuarioSelecionado.id : '') + '/redefinir-senha'">
            @csrf

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
                    class="w-full py-3.5 px-4 rounded-xl bg-[#2563eb] hover:bg-blue-700 text-white font-semibold transition-colors text-sm shadow-sm cursor-pointer"
                >
                    Enviar nova senha
                </button>
            </div>
        </form>

    </div>
</div>