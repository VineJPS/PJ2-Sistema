<div 
    x-show="modalAberta === 'alertaPermissao'" 
    x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm p-4"
    @keydown.escape.window="modalAberta = null"
>
    <div 
        @click.away="modalAberta = null"
        class="bg-white w-full max-w-[460px] rounded-[24px] shadow-2xl border border-slate-100 p-8 relative text-center"
    >
        <!-- Botão Fechar (X) -->
        <button 
            type="button" 
            @click="modalAberta = null" 
            class="absolute top-6 right-6 text-slate-400 hover:text-slate-600 transition-colors cursor-pointer"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>

        <!-- Ícone de Alerta -->
        <div class="w-14 h-14 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center mx-auto mb-4 border border-amber-100 shadow-sm">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>

        <h3 class="text-xl font-bold text-slate-900 mb-2">Permissão Insuficiente</h3>
        <p class="text-slate-600 text-sm leading-relaxed mb-6">
            Você não possui permissão para gerenciar o painel. Solicite acesso a um administrador.
        </p>

        <button 
            type="button" 
            @click="modalAberta = null" 
            class="w-full py-3 px-5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-semibold text-sm transition-colors cursor-pointer shadow-sm"
        >
            Entendi
        </button>
    </div>
</div>
